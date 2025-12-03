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
                $table->foreignId('pasien_id')->constrained('pasiens')->cascadeOnDelete();
                $table->foreignId('dokter_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
                $table->date('tanggal')->nullable();
                $table->time('jam_mulai')->nullable();
                $table->time('jam_selesai')->nullable();
                $table->text('keluhan');
                $table->text('keterangan')->nullable();
                $table->text('diagnosa')->nullable();
                $table->text('catatan')->nullable();
                $table->unsignedTinyInteger('nilai')->nullable();
                $table->unsignedTinyInteger('nilai_dosen')->nullable();
                $table->unsignedTinyInteger('nilai_komunikasi')->nullable();
                $table->unsignedTinyInteger('nilai_anamnesis')->nullable();
                $table->unsignedTinyInteger('nilai_diagnosa')->nullable();
                $table->unsignedTinyInteger('nilai_empati')->nullable();
                $table->text('catatan_dosen')->nullable();
                $table->unsignedTinyInteger('rating')->nullable();
                $table->text('komentar_rating')->nullable();
                $table->enum('status', [
                    'Menunggu',
                    'Terkonfirmasi',
                    'Berlangsung',
                    'Pergantian Sesi',
                    'Selesai',
                    'Dibatalkan',
                    'Ditolak',
                    'Terlambat',
                ])->default('Menunggu');
                $table->text('alasan_tolak')->nullable();
                $table->text('alasan_batal')->nullable();
                $table->text('alasan_terlambat')->nullable();
                $table->date('tanggal_baru')->nullable();
                $table->time('jam_mulai_baru')->nullable();
                $table->time('jam_selesai_baru')->nullable();
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