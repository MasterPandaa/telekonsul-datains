<?php
namespace App\Http\Controllers;
use App\Models\Pasien;
use App\Services\LogService;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index() {
        $pasiens = Pasien::all();
        return view('admin.pasien.index', compact('pasiens'));
    }
    public function create() {
        return view('admin.pasien.create');
    }
    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:pasiens',
            'email' => 'required|email|unique:pasiens',
        ]);
        $pasien = Pasien::create($request->all());
        
        // Catat aktivitas
        LogService::logActivity('create', 'Pasien', $pasien);
        
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil ditambahkan');
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
        $pasien->update($request->all());
        
        // Catat aktivitas
        LogService::logActivity('update', 'Pasien', [
            'id' => $pasien->id,
            'old' => $oldData,
            'new' => $pasien->toArray()
        ]);
        
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diupdate');
    }
    public function destroy(Pasien $pasien) {
        $pasienData = $pasien->toArray();
        $pasien->delete();
        
        // Catat aktivitas
        LogService::logActivity('delete', 'Pasien', $pasienData);
        
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil dihapus');
    }
} 