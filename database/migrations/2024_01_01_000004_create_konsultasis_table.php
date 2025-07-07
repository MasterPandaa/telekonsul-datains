<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('konsultasis')) {
            Schema::create('konsultasis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
                $table->foreignId('dokter_id')->nullable()->constrained('dokters')->nullOnDelete();
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
                $table->string('kode_konsultasi')->unique();
                $table->string('judul');
                $table->text('keluhan');
                $table->enum('status', ['Menunggu Konfirmasi', 'Diterima', 'Ditolak', 'Selesai', 'Dibatalkan'])->default('Menunggu Konfirmasi');
                $table->dateTime('tanggal_konsultasi')->nullable();
                $table->dateTime('tanggal_selesai')->nullable();
                $table->text('diagnosa')->nullable();
                $table->text('catatan_dokter')->nullable();
                $table->text('catatan_dosen')->nullable();
                $table->integer('nilai_dosen')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
}; 