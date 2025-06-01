<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->unique();
            $table->string('email')->unique();
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dosens');
    }
}; 