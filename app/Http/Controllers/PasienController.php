<?php
namespace App\Http\Controllers;
use App\Models\Pasien;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    public function index(Request $request) {
        $query = Pasien::query()->with('user');
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
            })->orWhere(function($q) use ($searchTerm) {
                $q->where('nik', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('no_hp', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Urutkan data
        $sortBy = $request->sort_by ?? 'id';
        $sortOrder = $request->sort_order ?? 'asc';
        
        if ($sortBy === 'nama') {
            $query->join('users', 'pasiens.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortOrder)
                  ->select('pasiens.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        // Pagination
        $pasiens = $query->paginate(10)->withQueryString();
        
        return view('admin.pasien.index', [
            'pasiens' => $pasiens,
            'searchTerm' => $request->search ?? '',
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }
    
    public function create() {
        return view('admin.pasien.create');
    }
    
    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:pasiens',
            'email' => 'required|email|unique:pasiens|unique:users',
        ]);
        
        DB::beginTransaction();
        try {
            // Buat user terlebih dahulu
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make('pasien123'), // Default password
                'role' => 'pasien'
            ]);
            
            // Buat pasien dengan relasi ke user
            $pasienData = $request->except('nama');
            $pasienData['user_id'] = $user->id;
            
            $pasien = Pasien::create($pasienData);
            
            DB::commit();
            
            // Catat aktivitas
            LogService::logActivity('create', 'Pasien', $pasien);
            
            return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil ditambahkan');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function edit(Pasien $pasien) {
        return view('admin.pasien.edit', compact('pasien'));
    }
    
    public function update(Request $request, Pasien $pasien) {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:pasiens,nik,'.$pasien->id,
            'email' => 'required|email|unique:pasiens,email,'.$pasien->id,
        ]);
        
        $oldData = $pasien->toArray();
        
        DB::beginTransaction();
        try {
            // Update user name
            if ($pasien->user) {
                $pasien->user->update([
                    'name' => $request->nama,
                    'email' => $request->email
                ]);
            }
            
            // Update pasien data
            $pasienData = $request->except('nama');
            $pasien->update($pasienData);
            
            DB::commit();
            
            // Catat aktivitas
            LogService::logActivity('update', 'Pasien', [
                'id' => $pasien->id,
                'old' => $oldData,
                'new' => $pasien->toArray()
            ]);
            
            return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diupdate');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function destroy(Pasien $pasien) {
        $pasienData = $pasien->toArray();
        
        DB::beginTransaction();
        try {
            // Hapus user jika ada
            if ($pasien->user) {
                $pasien->user->delete();
            }
            
            // Hapus data pasien
            $pasien->delete();
            
            DB::commit();
            
            // Catat aktivitas
            LogService::logActivity('delete', 'Pasien', $pasienData);
            
            return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Pasien $pasien) {
        // Menampilkan detail data pasien
        return view('admin.pasien.show', compact('pasien'));
    }
} 