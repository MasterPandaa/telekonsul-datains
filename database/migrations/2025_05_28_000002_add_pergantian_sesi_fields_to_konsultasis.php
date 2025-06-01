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
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->text('alasan_tolak')->nullable()->after('status');
            $table->text('alasan_batal')->nullable()->after('alasan_tolak');
            $table->text('alasan_terlambat')->nullable()->after('alasan_batal');
            $table->date('tanggal_baru')->nullable()->after('alasan_terlambat');
            $table->time('jam_mulai_baru')->nullable()->after('tanggal_baru');
            $table->time('jam_selesai_baru')->nullable()->after('jam_mulai_baru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn([
                'alasan_tolak',
                'alasan_batal',
                'alasan_terlambat',
                'tanggal_baru',
                'jam_mulai_baru',
                'jam_selesai_baru'
            ]);
        });
    }
}; 