<?php
namespace App\Http\Controllers;
use App\Models\Dokter;
use App\Models\User;
use App\Support\ProfilePhoto;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

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
            'jenis_kelamin' => ['required','in:Laki-laki,Perempuan'],
            'no_sip' => ['required','string','max:50','regex:/^[A-Z0-9\/\.\-]{5,50}$/','unique:dokters,no_sip'],
            'no_str' => ['required','digits:13','unique:dokters,no_str'],
            'email' => ['required','email:rfc,dns','max:255','unique:users,email','unique:dokters,email'],
            'no_hp' => ['required','regex:/^08[0-9]{8,11}$/'],
            'password' => ['required','confirmed', 'min:8'],
        ], [
            'nama.required' => 'Nama dokter wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'no_sip.required' => 'Nomor SIP wajib diisi',
            'no_sip.regex' => 'Format Nomor SIP terdiri dari kombinasi huruf kapital, angka, atau karakter /. -',
            'no_sip.unique' => 'Nomor SIP sudah terdaftar',
            'no_str.required' => 'Nomor STR wajib diisi',
            'no_str.digits' => 'Nomor STR harus berisi 13 digit angka',
            'no_str.unique' => 'Nomor STR sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'password.min' => 'Password minimal 8 karakter',
        ]);
        
        // Normalisasi data penting
        $validatedData['no_sip'] = strtoupper($validatedData['no_sip']);
        $validatedData['no_str'] = preg_replace('/\D+/', '', $validatedData['no_str']);
        
        // Buat user terlebih dahulu
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'dokter'
            ]);
            
            // Buat dokter dengan relasi ke user
            $dokterData = collect($validatedData)->except(['nama', 'password'])->toArray();
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
            'jenis_kelamin' => ['required','in:Laki-laki,Perempuan'],
            'no_sip' => ['required','string','max:50','regex:/^[A-Z0-9\/\.\-]{5,50}$/','unique:dokters,no_sip,'.$dokter->id],
            'no_str' => ['required','digits:13','unique:dokters,no_str,'.$dokter->id],
            'email' => ['required','email:rfc,dns','max:255','unique:users,email,' . ($dokter->user_id ?? 'null') . ',id','unique:dokters,email,'.$dokter->id],
            'no_hp' => ['required','regex:/^08[0-9]{8,11}$/'],
            'alamat' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'universitas' => 'nullable|string|max:150',
            'tahun_lulus' => ['nullable','integer','between:1950,' . now()->year],
            'spesialisasi' => 'nullable|string|max:100',
            'tempat_praktik' => 'nullable|string|max:255',
            'rumah_sakit' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'password' => ['nullable','confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'nama.required' => 'Nama dokter wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'no_sip.required' => 'Nomor SIP wajib diisi',
            'no_sip.regex' => 'Format Nomor SIP terdiri dari kombinasi huruf kapital, angka, atau karakter /. -',
            'no_sip.unique' => 'Nomor SIP sudah terdaftar',
            'no_str.required' => 'Nomor STR wajib diisi',
            'no_str.digits' => 'Nomor STR harus berisi 13 digit angka',
            'no_str.unique' => 'Nomor STR sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);
        
        $validatedData['no_sip'] = strtoupper($validatedData['no_sip']);
        $validatedData['no_str'] = preg_replace('/\D+/', '', $validatedData['no_str']);
        
        $oldData = $dokter->toArray();
        
        DB::beginTransaction();
        try {
            // Update user name
            if ($dokter->user) {
                $userUpdates = [
                    'name' => $validatedData['nama'],
                    'email' => $validatedData['email']
                ];

                if (!empty($validatedData['password'])) {
                    $userUpdates['password'] = Hash::make($validatedData['password']);
                }

                $dokter->user->update($userUpdates);
            }

            if ($request->hasFile('foto')) {
                if (!$dokter->user) {
                    throw new \Exception('User terkait tidak ditemukan');
                }

                ProfilePhoto::deleteIfExists($dokter->user->profile_photo_path ?? null);
                $path = ProfilePhoto::storeForUser($request->file('foto'), (int) $dokter->user->id);
                $dokter->user->profile_photo_path = $path;
                $dokter->user->save();
            }
            
            // Update dokter data
            $dokterData = collect($validatedData)->except(['nama', 'password', 'password_confirmation', 'foto'])->toArray();
            foreach (['tempat_lahir','tanggal_lahir','universitas','tahun_lulus','alamat','spesialisasi','tempat_praktik','rumah_sakit'] as $nullableField) {
                if (array_key_exists($nullableField, $dokterData) && $dokterData[$nullableField] === '') {
                    $dokterData[$nullableField] = null;
                }
            }

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

    /**
     * Cek ketersediaan nomor SIP
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSip(Request $request)
    {
        $validated = $request->validate([
            'no_sip' => ['required', 'string', 'max:50'],
        ]);

        $value = strtoupper(trim($validated['no_sip']));
        $exists = Dokter::whereRaw('LOWER(no_sip) = ?', [strtolower($value)])->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Nomor SIP sudah terdaftar' : 'Nomor SIP tersedia untuk digunakan',
        ]);
    }

    /**
     * Cek ketersediaan nomor STR
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStr(Request $request)
    {
        $validated = $request->validate([
            'no_str' => ['required', 'string', 'regex:/^\d{13}$/'],
        ]);

        $value = preg_replace('/\D+/', '', $validated['no_str']);
        $exists = Dokter::where('no_str', $value)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Nomor STR sudah terdaftar' : 'Nomor STR tersedia untuk digunakan',
        ]);
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