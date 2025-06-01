<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('email')->unique();
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('foto')->nullable();
            
            // Informasi medis
            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->string('tekanan_darah')->nullable();
            $table->text('alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            
            // Relasi dengan user
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pasiens');
    }
}; 