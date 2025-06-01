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
        // Mengubah enum status untuk menambahkan nilai 'Terlambat'
        DB::statement("ALTER TABLE konsultasis MODIFY COLUMN status ENUM('Menunggu', 'Terkonfirmasi', 'Ditolak', 'Selesai', 'Dibatalkan', 'Terlambat') DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan enum status ke nilai semula
        DB::statement("ALTER TABLE konsultasis MODIFY COLUMN status ENUM('Menunggu', 'Terkonfirmasi', 'Ditolak', 'Selesai', 'Dibatalkan') DEFAULT 'Menunggu'");
    }
}; 