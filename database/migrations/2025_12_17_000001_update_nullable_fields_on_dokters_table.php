<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->string('universitas')->nullable()->change();
            $table->year('tahun_lulus')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->string('universitas')->nullable(false)->change();
            $table->year('tahun_lulus')->nullable(false)->change();
        });
    }
};
