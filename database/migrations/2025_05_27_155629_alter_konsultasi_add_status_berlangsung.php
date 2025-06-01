<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mendapatkan status enum saat ini
        $tablePrefix = config('database.connections.mysql.prefix', '');
        $enumColumn = DB::select("SHOW COLUMNS FROM {$tablePrefix}konsultasis WHERE Field = 'status'")[0]->Type;
        
        // Extract enum values
        preg_match('/^enum\((.*)\)$/', $enumColumn, $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");
        
        // Periksa apakah 'Berlangsung' sudah ada dalam enum
        if (!in_array('Berlangsung', $currentValues)) {
            $currentValues[] = 'Berlangsung';
            $newEnumValues = "'" . implode("','", $currentValues) . "'";
            
            // Alter table untuk mengubah enum values
            DB::statement("ALTER TABLE {$tablePrefix}konsultasis MODIFY COLUMN status ENUM($newEnumValues) NOT NULL DEFAULT 'Menunggu'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mendapatkan status enum saat ini
        $tablePrefix = config('database.connections.mysql.prefix', '');
        $enumColumn = DB::select("SHOW COLUMNS FROM {$tablePrefix}konsultasis WHERE Field = 'status'")[0]->Type;
        
        // Extract enum values
        preg_match('/^enum\((.*)\)$/', $enumColumn, $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");
        
        // Hapus 'Berlangsung' dari enum
        $newValues = array_filter($currentValues, function($value) {
            return $value != 'Berlangsung';
        });
        
        $newEnumValues = "'" . implode("','", $newValues) . "'";
        
        // Alter table untuk mengubah enum values
        DB::statement("ALTER TABLE {$tablePrefix}konsultasis MODIFY COLUMN status ENUM($newEnumValues) NOT NULL DEFAULT 'Menunggu'");
    }
};
