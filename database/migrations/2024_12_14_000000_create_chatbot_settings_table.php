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
        Schema::create('chatbot_settings', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_url')->nullable();
            $table->string('method', 10)->default('POST');
            $table->unsignedSmallInteger('timeout')->default(15);
            $table->boolean('allow_insecure_ssl')->default(false);
            $table->string('auth_type', 20)->default('none');
            $table->string('basic_user')->nullable();
            $table->string('basic_pass')->nullable();
            $table->text('bearer_token')->nullable();
            $table->string('header_key')->nullable();
            $table->text('header_value')->nullable();
            $table->text('jwt_token')->nullable();
            $table->timestamps();
        });

        DB::table('chatbot_settings')->insert([
            'webhook_url' => env('CHATBOT_N8N_WEBHOOK_URL'),
            'method' => env('CHATBOT_N8N_METHOD', 'POST'),
            'timeout' => env('CHATBOT_N8N_TIMEOUT', 15),
            'allow_insecure_ssl' => filter_var(env('CHATBOT_N8N_ALLOW_INSECURE_SSL', false), FILTER_VALIDATE_BOOLEAN),
            'auth_type' => env('CHATBOT_N8N_AUTH_TYPE', 'none'),
            'basic_user' => env('CHATBOT_N8N_BASIC_USER'),
            'basic_pass' => env('CHATBOT_N8N_BASIC_PASS'),
            'bearer_token' => env('CHATBOT_N8N_BEARER_TOKEN'),
            'header_key' => env('CHATBOT_N8N_HEADER_KEY'),
            'header_value' => env('CHATBOT_N8N_HEADER_VALUE'),
            'jwt_token' => env('CHATBOT_N8N_JWT_TOKEN'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_settings');
    }
};
