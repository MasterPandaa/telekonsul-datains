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
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('keluhan');
            $table->text('keterangan')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('nilai')->nullable();
            $table->integer('nilai_dosen')->nullable()->comment('Nilai dari dosen (1-100)');
            $table->integer('nilai_komunikasi')->nullable()->comment('Nilai aspek komunikasi (1-100)');
            $table->integer('nilai_anamnesis')->nullable()->comment('Nilai aspek anamnesis (1-100)');
            $table->integer('nilai_diagnosa')->nullable()->comment('Nilai aspek diagnosa (1-100)');
            $table->integer('nilai_empati')->nullable()->comment('Nilai aspek empati (1-100)');
            $table->text('catatan_dosen')->nullable()->comment('Catatan penilaian dari dosen');
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