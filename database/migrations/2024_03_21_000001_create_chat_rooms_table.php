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
        if (!Schema::hasTable('chat_rooms')) {
            Schema::create('chat_rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('konsultasi_id')->constrained()->cascadeOnDelete();
                $table->uuid('room_id')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
}; 