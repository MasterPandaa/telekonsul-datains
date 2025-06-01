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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('foto')->nullable();
            $table->string('spesialisasi')->nullable();
            $table->integer('semester')->nullable();
            $table->integer('tahun_masuk')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->string('status')->default('Aktif');
            $table->string('pembimbing')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'foto',
                'spesialisasi',
                'semester',
                'tahun_masuk',
                'ipk',
                'status',
                'pembimbing',
            ]);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
