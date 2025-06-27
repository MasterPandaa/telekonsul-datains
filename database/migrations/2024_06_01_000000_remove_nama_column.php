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
        // Menghapus kolom nama dari tabel dokters, pasiens, dan dosens
        // karena akan menggunakan kolom name dari tabel users
        
        if (Schema::hasColumn('dokters', 'nama')) {
            Schema::table('dokters', function (Blueprint $table) {
                $table->dropColumn('nama');
            });
        }
        
        if (Schema::hasColumn('pasiens', 'nama')) {
            Schema::table('pasiens', function (Blueprint $table) {
                $table->dropColumn('nama');
            });
        }
        
        if (Schema::hasColumn('dosens', 'nama')) {
            Schema::table('dosens', function (Blueprint $table) {
                $table->dropColumn('nama');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan kolom nama jika migrasi di-rollback
        Schema::table('dokters', function (Blueprint $table) {
            $table->string('nama')->after('user_id');
        });
        
        Schema::table('pasiens', function (Blueprint $table) {
            $table->string('nama')->after('user_id');
        });
        
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('nama')->after('user_id');
        });
    }
}; 