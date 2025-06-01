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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // Jenis notifikasi: konsultasi_baru, rating_baru, dll
            $table->string('message'); // Pesan notifikasi
            $table->string('link')->nullable(); // Tautan yang akan dibuka saat notifikasi diklik
            $table->boolean('is_read')->default(false); // Status dibaca
            $table->json('data')->nullable(); // Data tambahan dalam format JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
