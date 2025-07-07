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
        // Migrasi ini tidak membuat atau mengubah struktur tabel
        // Hanya memastikan bahwa nama di tabel users sudah sesuai
        // Tidak perlu melakukan apa-apa karena kolom nama sudah dihapus
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu melakukan apa-apa saat rollback
        // Karena migrasi ini hanya memastikan data sesuai
    }
}; 