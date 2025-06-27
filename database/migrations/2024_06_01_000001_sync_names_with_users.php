<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Karena kolom nama sudah dihapus, kita perlu memastikan bahwa
        // setidaknya nama di tabel users tidak kosong
        
        // Update users yang terkait dengan dokters
        $users = DB::table('users')
            ->where('role', 'dokter')
            ->whereNull('name')
            ->orWhere('name', '')
            ->get();
            
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => 'Dokter ' . $user->id]);
        }
        
        // Update users yang terkait dengan pasiens
        $users = DB::table('users')
            ->where('role', 'pasien')
            ->whereNull('name')
            ->orWhere('name', '')
            ->get();
            
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => 'Pasien ' . $user->id]);
        }
        
        // Update users yang terkait dengan dosens
        $users = DB::table('users')
            ->where('role', 'dosen')
            ->whereNull('name')
            ->orWhere('name', '')
            ->get();
            
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => 'Dosen ' . $user->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada operasi yang perlu di-rollback
    }
}; 