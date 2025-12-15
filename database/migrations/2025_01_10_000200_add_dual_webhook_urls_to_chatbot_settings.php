<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chatbot_settings', function (Blueprint $table) {
            $table->string('webhook_url_prod')->nullable()->after('webhook_url');
            $table->string('webhook_url_test')->nullable()->after('webhook_url_prod');
        });

        DB::table('chatbot_settings')->whereNotNull('webhook_url')->update([
            'webhook_url_prod' => DB::raw("COALESCE(webhook_url_prod, webhook_url)"),
            'webhook_url_test' => DB::raw("COALESCE(webhook_url_test, webhook_url)"),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_settings', function (Blueprint $table) {
            $table->dropColumn(['webhook_url_prod', 'webhook_url_test']);
        });
    }
};
