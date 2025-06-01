<?php
namespace App\Http\Controllers;
use App\Models\Dosen;
use App\Services\LogService;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request) {
        $query = Dosen::query();
        
        // Filter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nip', 'LIKE', "%{$searchTerm}%")
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
        $dosens = $query->paginate(10)->withQueryString();
        
        return view('admin.dosen.index', [
            'dosens' => $dosens,
            'title' => 'Data Dosen',
            'searchTerm' => $request->search ?? '',
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }
    
    public function create() {
        return view('admin.dosen.create', [
            'title' => 'Tambah Dosen Baru'
        ]);
    }
    
    public function store(Request $request) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens',
            'email' => 'required|email|max:255|unique:dosens',
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
        
        $dosen = Dosen::create($validatedData);
        
        // Catat aktivitas
        try {
            LogService::logActivity('create', 'Dosen', $dosen);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan');
    }
    
    public function show(Dosen $dosen) {
        return view('admin.dosen.show', [
            'dosen' => $dosen,
            'title' => 'Detail Dosen'
        ]);
    }
    
    public function edit(Dosen $dosen) {
        return view('admin.dosen.edit', [
            'dosen' => $dosen,
            'title' => 'Edit Data Dosen'
        ]);
    }
    
    public function update(Request $request, Dosen $dosen) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens,nip,'.$dosen->id,
            'email' => 'required|email|max:255|unique:dosens,email,'.$dosen->id,
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
        $dosen->update($validatedData);
        
        // Catat aktivitas
        try {
            LogService::logActivity('update', 'Dosen', [
                'old' => $oldData,
                'new' => $dosen->toArray()
            ]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui');
    }
    
    public function destroy(Dosen $dosen) {
        $dosenData = $dosen->toArray();
        
        // Hapus data
        $dosen->delete();
        
        // Catat aktivitas
        try {
            LogService::logActivity('delete', 'Dosen', $dosenData);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Gagal mencatat aktivitas: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil dihapus');
    }
} 