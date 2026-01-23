<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Mengubah primary key pada tabel chat_rooms dari Auto-Increment Integer menjadi UUID.
     * Ini memperbaiki masalah keamanan ID dan mencegah data race condition.
     */
    public function up(): void
    {
        // Step 1: Drop foreign key constraint on chat_messages
        if (Schema::hasTable('chat_messages') && Schema::hasColumn('chat_messages', 'chat_room_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                // Check if foreign key exists before dropping
                $foreignKeys = $this->listTableForeignKeys('chat_messages');
                if (in_array('chat_messages_chat_room_id_foreign', $foreignKeys)) {
                    $table->dropForeign(['chat_room_id']);
                }
            });
        }

        // Step 2: Add new UUID column to chat_rooms
        if (!Schema::hasColumn('chat_rooms', 'uuid')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Step 3: Generate UUIDs for existing records
        DB::table('chat_rooms')->whereNull('uuid')->get()->each(function ($room) {
            DB::table('chat_rooms')
                ->where('id', $room->id)
                ->update(['uuid' => Str::uuid()->toString()]);
        });

        // Step 4: Update chat_messages to reference new UUID
        if (Schema::hasTable('chat_messages') && !Schema::hasColumn('chat_messages', 'chat_room_uuid')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->uuid('chat_room_uuid')->nullable()->after('chat_room_id');
            });
        }

        // Step 5: Copy UUID references to chat_messages
        if (Schema::hasColumn('chat_messages', 'chat_room_uuid')) {
            DB::statement('
                UPDATE chat_messages 
                SET chat_room_uuid = (
                    SELECT uuid FROM chat_rooms WHERE chat_rooms.id = chat_messages.chat_room_id
                )
                WHERE chat_room_uuid IS NULL
            ');
        }

        // Step 6: Drop old chat_room_id column and rename chat_room_uuid to chat_room_id
        if (Schema::hasColumn('chat_messages', 'chat_room_id') && Schema::hasColumn('chat_messages', 'chat_room_uuid')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropColumn('chat_room_id');
            });

            Schema::table('chat_messages', function (Blueprint $table) {
                $table->renameColumn('chat_room_uuid', 'chat_room_id');
            });
        }

        // Step 7: Drop old id column and rename uuid to id
        if (Schema::hasColumn('chat_rooms', 'id') && Schema::hasColumn('chat_rooms', 'uuid')) {
            // First, drop the primary key
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->dropPrimary();
            });

            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->renameColumn('uuid', 'id');
            });

            // Set UUID as primary key
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->primary('id');
            });
        }

        // Step 8: Re-add foreign key constraint with UUID
        if (Schema::hasTable('chat_messages') && Schema::hasColumn('chat_messages', 'chat_room_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->foreign('chat_room_id')
                    ->references('id')
                    ->on('chat_rooms')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback is complex due to data type change
        // Recommend fresh migration or database restore if rollback needed

        // Drop foreign key first
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('chat_messages');
                if (in_array('chat_messages_chat_room_id_foreign', $foreignKeys)) {
                    $table->dropForeign(['chat_room_id']);
                }
            });
        }
    }

    /**
     * Get list of foreign keys for a table
     */
    private function listTableForeignKeys(string $table): array
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();

        try {
            $foreignKeys = $dbSchemaManager->listTableForeignKeys($table);
            return array_map(fn($fk) => $fk->getName(), $foreignKeys);
        } catch (\Exception $e) {
            return [];
        }
    }
};
