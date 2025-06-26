<?php
namespace App\Http\Controllers;
use App\Models\Dosen;
use App\Models\Konsultasi;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:dosens',
            'email' => 'required|email|unique:dosens',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dosen = Dosen::create($validator->validated());
        
        // Catat aktivitas
        $this->logService->store(
            'Menambahkan data dosen baru: ' . $dosen->nama,
            'create',
            json_encode($dosen),
            'dosen'
        );
        
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:dosens,nip,' . $dosen->id,
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldData = $dosen->toArray();
        $dosen->update($validator->validated());
        
        // Catat aktivitas
        $this->logService->store(
            'Mengubah data dosen: ' . $dosen->nama,
            'update',
            json_encode(['old' => $oldData, 'new' => $dosen]),
            'dosen'
        );
        
        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui');
    }
    
    public function destroy(Dosen $dosen) {
        $dosenData = $dosen->toArray();
        
        // Hapus data
        $dosen->delete();
        
        // Catat aktivitas
        $this->logService->store(
            'Menghapus data dosen: ' . $dosen->nama,
            'delete',
            json_encode($dosenData),
            'dosen'
        );
        
        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil dihapus');
    }

    // Dosen Dashboard Methods
    public function dashboard()
    {
        $totalKonsultasi = Konsultasi::count();
        $konsultasiSelesai = Konsultasi::where('status', 'Selesai')->count();
        $konsultasiPending = Konsultasi::where('status', 'Menunggu')->count();
        $rataRataRating = Konsultasi::whereNotNull('rating')->avg('rating') ?? 0;
        
        // Ambil 5 konsultasi terbaru
        $konsultasiTerbaru = Konsultasi::with(['pasien', 'dokter'])
            ->latest()
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'totalKonsultasi',
            'konsultasiSelesai',
            'konsultasiPending',
            'rataRataRating',
            'konsultasiTerbaru'
        ));
    }

    // Dosen Profile Methods
    public function profilIndex()
    {
        $dosen = auth()->user()->dosen;
        return view('dosen.profil.index', compact('dosen'));
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $dosen = auth()->user()->dosen;
        $oldFoto = $dosen->foto;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = 'dosen_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/img/dosen', $filename);

            if ($oldFoto && $oldFoto != 'default.jpg') {
                Storage::delete('public/img/dosen/' . $oldFoto);
            }

            $dosen->update(['foto' => $filename]);

            // Catat aktivitas
            $this->logService->store(
                'Mengubah foto profil dosen',
                'update',
                json_encode(['old' => $oldFoto, 'new' => $filename]),
                'dosen'
            );
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }

    public function updateInformasi(Request $request)
    {
        $dosen = auth()->user()->dosen;
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldData = $dosen->toArray();
        $dosen->update($validator->validated());

        // Catat aktivitas
        $this->logService->store(
            'Mengubah informasi profil dosen',
            'update',
            json_encode(['old' => $oldData, 'new' => $dosen]),
            'dosen'
        );

        return redirect()->back()->with('success', 'Informasi profil berhasil diperbarui');
    }

    // Supervisi Methods
    public function supervisiIndex()
    {
        $konsultasis = Konsultasi::with(['pasien', 'dokter'])
            ->latest()
            ->paginate(10);

        return view('dosen.supervisi.index', compact('konsultasis'));
    }

    public function supervisiShow(Konsultasi $konsultasi)
    {
        $konsultasi->load(['pasien', 'dokter', 'chatRoom.messages']);
        return view('dosen.supervisi.show', compact('konsultasi'));
    }

    public function supervisiNilai(Request $request, Konsultasi $konsultasi)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $konsultasi->update([
            'nilai_supervisi' => $request->nilai,
            'catatan_supervisi' => $request->catatan
        ]);

        // Catat aktivitas
        $this->logService->store(
            'Memberikan nilai supervisi untuk konsultasi ID: ' . $konsultasi->id,
            'update',
            json_encode(['nilai' => $request->nilai, 'catatan' => $request->catatan]),
            'konsultasi'
        );

        return redirect()->route('dosen.supervisi.index')
            ->with('success', 'Nilai supervisi berhasil disimpan');
    }

    // Penilaian Methods
    public function penilaianIndex()
    {
        $konsultasis = Konsultasi::with(['pasien', 'dokter'])
            ->whereNotNull('nilai_supervisi')
            ->latest()
            ->paginate(10);

        return view('dosen.penilaian.index', compact('konsultasis'));
    }
} 