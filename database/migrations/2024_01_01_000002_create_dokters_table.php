<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_sip')->unique();
            $table->string('no_str')->unique();
            $table->string('email')->unique();
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('foto')->default('img/dokter/default.jpg');
            $table->string('spesialisasi')->nullable();
            $table->string('sub_spesialisasi')->nullable();
            $table->string('universitas');
            $table->year('tahun_lulus');
            $table->string('tempat_praktik')->nullable();
            $table->string('rumah_sakit')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Cuti'])->default('Aktif');
            $table->text('pengalaman')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dokters');
    }
}; 