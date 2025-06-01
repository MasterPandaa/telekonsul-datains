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
            $table->unsignedTinyInteger('rating')->nullable()->after('nilai')->comment('Rating dari pasien (1-5 bintang)');
            $table->text('komentar_rating')->nullable()->after('rating')->comment('Komentar tambahan untuk rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn(['rating', 'komentar_rating']);
        });
    }
};
