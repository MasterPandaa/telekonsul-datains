<?php
namespace App\Http\Controllers;
use App\Models\Pasien;
use App\Models\User;
use App\Support\ProfilePhoto;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:pasiens,nik',
            'email' => 'required|email|max:255|unique:users,email|unique:pasiens,email',
            'alamat' => 'nullable|string|max:1000',
            'no_hp' => ['nullable', 'regex:/^08[0-9]{8,11}$/'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'nama.required' => 'Nama pasien wajib diisi',
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);
        
        DB::beginTransaction();
        try {
            // Buat user terlebih dahulu
            $user = User::create([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'pasien'
            ]);
            
            // Buat pasien dengan relasi ke user
            $pasienData = collect($validatedData)->except(['nama', 'password', 'password_confirmation'])->toArray();
            if (array_key_exists('alamat', $pasienData) && $pasienData['alamat'] === '') {
                $pasienData['alamat'] = null;
            }
            if (array_key_exists('no_hp', $pasienData) && $pasienData['no_hp'] === '') {
                $pasienData['no_hp'] = null;
            }
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
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:pasiens,nik,' . $pasien->id,
            'email' => 'required|email|max:255|unique:users,email,' . ($pasien->user_id ?? 'null') . ',id|unique:pasiens,email,' . $pasien->id,
            'alamat' => 'nullable|string|max:1000',
            'no_hp' => ['nullable', 'regex:/^08[0-9]{8,11}$/'],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'nama.required' => 'Nama pasien wajib diisi',
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'foto.image' => 'Foto harus berupa gambar',
        ]);
        
        $oldData = $pasien->toArray();
        
        DB::beginTransaction();
        try {
            // Update user name
            if ($pasien->user) {
                $userUpdates = [
                    'name' => $validatedData['nama'],
                    'email' => $validatedData['email'],
                ];

                if (!empty($validatedData['password'])) {
                    $userUpdates['password'] = Hash::make($validatedData['password']);
                }

                $pasien->user->update($userUpdates);
            }
            
            // Update pasien data
            $pasienData = collect($validatedData)->except(['nama', 'password', 'password_confirmation', 'foto'])->toArray();
            foreach (['alamat', 'no_hp', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'tinggi_badan', 'berat_badan', 'tekanan_darah', 'alergi', 'riwayat_penyakit'] as $nullableField) {
                if (array_key_exists($nullableField, $pasienData) && $pasienData[$nullableField] === '') {
                    $pasienData[$nullableField] = null;
                }
            }

            if ($request->hasFile('foto')) {
                $fotoFile = $request->file('foto');
                $relativePath = ProfilePhoto::storeUploadedAsPng($fotoFile, (int) ($pasien->user_id ?? 0));
                $pasienData['foto'] = $relativePath;
            }
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