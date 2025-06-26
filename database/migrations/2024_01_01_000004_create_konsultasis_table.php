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
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->cascadeOnDelete();
            $table->foreignId('dokter_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('keluhan');
            $table->text('keterangan')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('nilai')->nullable();
            $table->unsignedTinyInteger('rating')->nullable()->comment('Rating dari pasien (1-5 bintang)');
            $table->text('komentar_rating')->nullable()->comment('Komentar tambahan untuk rating');
            $table->enum('status', [
                'Menunggu', 'Terkonfirmasi', 'Ditolak', 'Selesai', 'Dibatalkan', 'Terlambat', 'Berlangsung'
            ])->default('Menunggu');
            $table->text('alasan_tolak')->nullable();
            $table->text('alasan_batal')->nullable();
            $table->text('alasan_terlambat')->nullable();
            $table->date('tanggal_baru')->nullable();
            $table->time('jam_mulai_baru')->nullable();
            $table->time('jam_selesai_baru')->nullable();
            
            // Kolom untuk supervisi dosen
            $table->integer('nilai_supervisi')->nullable();
            $table->text('catatan_supervisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
}; 