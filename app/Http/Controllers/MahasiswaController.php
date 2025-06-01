<?php
namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use App\Services\LogService;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request) {
        $query = Mahasiswa::query();
        
        // Filter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nim', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('no_hp', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Urutkan data
        $sortBy = $request->sort_by ?? 'nama';
        $sortOrder = $request->sort_order ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $mahasiswas = $query->paginate(10)->withQueryString();
        
        return view('admin.mahasiswa.index', [
            'mahasiswas' => $mahasiswas,
            'title' => 'Data Mahasiswa',
            'searchTerm' => $request->search ?? '',
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }

    public function create() {
        return view('admin.mahasiswa.create', [
            'title' => 'Tambah Mahasiswa Baru'
        ]);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:mahasiswas',
            'email' => 'required|email|max:255|unique:mahasiswas',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);
        
        $mahasiswa = Mahasiswa::create($validatedData);
        
        // Catat aktivitas
        try {
            LogService::logActivity('create', 'Mahasiswa', $mahasiswa);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan');
    }
    
    public function show(Mahasiswa $mahasiswa) {
        return view('admin.mahasiswa.show', [
            'mahasiswa' => $mahasiswa,
            'title' => 'Detail Mahasiswa'
        ]);
    }

    public function edit(Mahasiswa $mahasiswa) {
        return view('admin.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa,
            'title' => 'Edit Data Mahasiswa'
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:mahasiswas,nim,'.$mahasiswa->id,
            'email' => 'required|email|max:255|unique:mahasiswas,email,'.$mahasiswa->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);
        
        $oldData = $mahasiswa->toArray();
        $mahasiswa->update($validatedData);
        
        // Catat aktivitas
        try {
            LogService::logActivity('update', 'Mahasiswa', [
                'old' => $oldData,
                'new' => $mahasiswa->toArray()
            ]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function destroy(Mahasiswa $mahasiswa) {
        $mahasiswaData = $mahasiswa->toArray();
        
        // Hapus data
        $mahasiswa->delete();
        
        // Catat aktivitas
        try {
            LogService::logActivity('delete', 'Mahasiswa', $mahasiswaData);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus');
    }
} 