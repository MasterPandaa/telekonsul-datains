<?php
namespace App\Http\Controllers;
use App\Models\Dokter;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function index(Request $request) {
        $query = Dokter::query()->with('user');
        
        // Filter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
            })->orWhere(function($q) use ($searchTerm) {
                $q->where('no_sip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('no_str', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('no_hp', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('spesialisasi', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Urutkan data
        $sortBy = $request->sort_by ?? 'id';
        $sortOrder = $request->sort_order ?? 'asc';
        
        if ($sortBy === 'nama') {
            $query->join('users', 'dokters.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortOrder)
                  ->select('dokters.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        // Pagination
        $dokters = $query->paginate(10)->withQueryString();
        
        return view('admin.dokter.index', [
            'dokters' => $dokters,
            'title' => 'Data Dokter',
            'searchTerm' => $request->search ?? '',
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }

    public function create() {
        return view('admin.dokter.create', [
            'title' => 'Tambah Dokter Baru'
        ]);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'no_sip' => 'required|string|max:50|unique:dokters',
            'no_str' => 'required|string|max:50|unique:dokters',
            'email' => 'required|email|max:255|unique:dokters|unique:users',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'spesialisasi' => 'nullable|string|max:100',
            'tempat_praktik' => 'nullable|string|max:255',
            'rumah_sakit' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama dokter wajib diisi',
            'no_sip.required' => 'Nomor SIP wajib diisi',
            'no_sip.unique' => 'Nomor SIP sudah terdaftar',
            'no_str.required' => 'Nomor STR wajib diisi',
            'no_str.unique' => 'Nomor STR sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);
        
        // Buat user terlebih dahulu
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make('dokter123'), // Default password
                'role' => 'dokter'
            ]);
            
            // Buat dokter dengan relasi ke user
            $dokterData = collect($validatedData)->except(['nama'])->toArray();
            $dokterData['user_id'] = $user->id;
            
            $dokter = Dokter::create($dokterData);
            
            DB::commit();
            
            // Catat aktivitas
            try {
                LogService::logActivity('create', 'Dokter', $dokter);
            } catch (\Exception $e) {
                // Log error jika terjadi masalah
                \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
            }
            
            return redirect()
                ->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function show(Dokter $dokter) {
        return view('admin.dokter.show', [
            'dokter' => $dokter,
            'title' => 'Detail Dokter'
        ]);
    }

    public function edit(Dokter $dokter) {
        return view('admin.dokter.edit', [
            'dokter' => $dokter,
            'title' => 'Edit Data Dokter'
        ]);
    }

    public function update(Request $request, Dokter $dokter) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'no_sip' => 'required|string|max:50|unique:dokters,no_sip,'.$dokter->id,
            'no_str' => 'required|string|max:50|unique:dokters,no_str,'.$dokter->id,
            'email' => 'required|email|max:255|unique:dokters,email,'.$dokter->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'spesialisasi' => 'nullable|string|max:100',
            'tempat_praktik' => 'nullable|string|max:255',
            'rumah_sakit' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama dokter wajib diisi',
            'no_sip.required' => 'Nomor SIP wajib diisi',
            'no_sip.unique' => 'Nomor SIP sudah terdaftar',
            'no_str.required' => 'Nomor STR wajib diisi',
            'no_str.unique' => 'Nomor STR sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);
        
        $oldData = $dokter->toArray();
        
        DB::beginTransaction();
        try {
            // Update user name
            if ($dokter->user) {
                $dokter->user->update([
                    'name' => $validatedData['nama'],
                    'email' => $validatedData['email']
                ]);
            }
            
            // Update dokter data
            $dokterData = collect($validatedData)->except(['nama'])->toArray();
            $dokter->update($dokterData);
            
            DB::commit();
            
            // Catat aktivitas
            try {
                LogService::logActivity('update', 'Dokter', [
                    'old' => $oldData,
                    'new' => $dokter->toArray()
                ]);
            } catch (\Exception $e) {
                // Log error jika terjadi masalah
                \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
            }
            
            return redirect()
                ->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Dokter $dokter) {
        $dokterData = $dokter->toArray();
        
        DB::beginTransaction();
        try {
            // Hapus user jika ada
            if ($dokter->user) {
                $dokter->user->delete();
            }
            
            // Hapus data dokter
            $dokter->delete();
            
            DB::commit();
            
            // Catat aktivitas
            try {
                LogService::logActivity('delete', 'Dokter', $dokterData);
            } catch (\Exception $e) {
                // Log error jika terjadi masalah
                \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
            }
            
            return redirect()
                ->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 