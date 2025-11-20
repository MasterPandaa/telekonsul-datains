<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::query()->with('user');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
            })->orWhere(function ($q) use ($searchTerm) {
                $q->where('nip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('no_hp', 'LIKE', "%{$searchTerm}%");
            });
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortOrder = $request->sort_order ?? 'asc';

        if ($sortBy === 'nama') {
            $query->join('users', 'dosens.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortOrder)
                ->select('dosens.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $dosens = $query->paginate(10)->withQueryString();

        return view('admin.dosen.index', [
            'dosens' => $dosens,
            'title' => 'Data Dosen',
            'searchTerm' => $request->search ?? '',
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }

    public function create()
    {
        return view('admin.dosen.create', [
            'title' => 'Tambah Dosen Baru'
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens',
            'email' => 'required|email|max:255|unique:dosens|unique:users',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ], [
            'nama.required' => 'Nama dosen wajib diisi',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make('dosen123'), // default password
                'role' => 'dosen',
            ]);

            $dosenData = collect($validatedData)->except(['nama'])->toArray();
            $dosenData['user_id'] = $user->id;

            $dosen = Dosen::create($dosenData);

            DB::commit();

            try {
                LogService::logActivity('create', 'Dosen', $dosen);
            } catch (\Exception $e) {
                \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
            }

            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Dosen $dosen)
    {
        return view('admin.dosen.show', [
            'dosen' => $dosen,
            'title' => 'Detail Dosen',
        ]);
    }

    public function edit(Dosen $dosen)
    {
        return view('admin.dosen.edit', [
            'dosen' => $dosen,
            'title' => 'Edit Data Dosen',
        ]);
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens,nip,' . $dosen->id,
            'email' => 'required|email|max:255|unique:dosens,email,' . $dosen->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ], [
            'nama.required' => 'Nama dosen wajib diisi',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        $oldData = $dosen->toArray();

        DB::beginTransaction();
        try {
            if ($dosen->user) {
                $dosen->user->update([
                    'name' => $validatedData['nama'],
                    'email' => $validatedData['email'],
                ]);
            }

            $dosenData = collect($validatedData)->except(['nama'])->toArray();
            $dosen->update($dosenData);

            DB::commit();

            try {
                LogService::logActivity('update', 'Dosen', [
                    'old' => $oldData,
                    'new' => $dosen->toArray(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
            }

            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Dosen $dosen)
    {
        $dosenData = $dosen->toArray();

        DB::beginTransaction();
        try {
            if ($dosen->user) {
                $dosen->user->delete();
            }

            $dosen->delete();
            DB::commit();

            try {
                LogService::logActivity('delete', 'Dosen', $dosenData);
            } catch (\Exception $e) {
                \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
            }

            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
