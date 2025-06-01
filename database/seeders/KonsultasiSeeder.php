<?php

namespace Database\Seeders;

use App\Models\Konsultasi;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KonsultasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua pasien yang ada
        $pasiens = Pasien::all();
        
        // Ambil semua mahasiswa yang ada
        $mahasiswas = User::where('role', 'mahasiswa')->get();
        
        // Jika tidak ada pasien atau mahasiswa, tidak perlu membuat konsultasi
        if ($pasiens->isEmpty() || $mahasiswas->isEmpty()) {
            return;
        }
        
        // Buat 5 konsultasi selesai
        foreach ($pasiens->take(5) as $pasien) {
            Konsultasi::factory()->selesai()->create([
                'pasien_id' => $pasien->id,
                'mahasiswa_id' => $mahasiswas->random()->id,
                'tanggal' => now()->subDays(rand(1, 30)),
            ]);
        }
        
        // Buat 3 konsultasi terkonfirmasi
        foreach ($pasiens->take(3) as $pasien) {
            Konsultasi::factory()->terkonfirmasi()->create([
                'pasien_id' => $pasien->id,
                'mahasiswa_id' => $mahasiswas->random()->id,
                'tanggal' => now()->addDays(rand(1, 7)),
            ]);
        }
        
        // Buat 2 konsultasi menunggu
        foreach ($pasiens->take(2) as $pasien) {
            Konsultasi::factory()->menunggu()->create([
                'pasien_id' => $pasien->id,
                'mahasiswa_id' => $mahasiswas->random()->id,
                'tanggal' => now()->addDays(rand(1, 14)),
            ]);
        }
    }
} 