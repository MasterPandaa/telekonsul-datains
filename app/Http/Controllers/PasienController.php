<?php
namespace App\Http\Controllers;
use App\Models\Pasien;
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
        Pasien::create($request->all());
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
        $pasien->update($request->all());
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diupdate');
    }
    public function destroy(Pasien $pasien) {
        $pasien->delete();
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil dihapus');
    }
} 