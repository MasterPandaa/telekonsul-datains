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
     * 
     * Migration ini handle kondisi partial state dimana uuid column sudah ada.
     */
    public function up(): void
    {
        // Step 1: Drop foreign key constraint on chat_messages (if exists)
        if (Schema::hasTable('chat_messages') && Schema::hasColumn('chat_messages', 'chat_room_id')) {
            $foreignKeys = $this->listTableForeignKeys('chat_messages');
            if (in_array('chat_messages_chat_room_id_foreign', $foreignKeys)) {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->dropForeign(['chat_room_id']);
                });
            }
        }

        // Step 2: Add new UUID column to chat_rooms (skip if already exists)
        if (!Schema::hasColumn('chat_rooms', 'uuid')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Step 3: Generate UUIDs for existing records that don't have one yet
        DB::table('chat_rooms')->whereNull('uuid')->get()->each(function ($room) {
            DB::table('chat_rooms')
                ->where('id', $room->id)
                ->update(['uuid' => Str::uuid()->toString()]);
        });

        // Step 4: Update chat_messages to reference new UUID (skip if already done)
        if (Schema::hasTable('chat_messages') && !Schema::hasColumn('chat_messages', 'chat_room_uuid')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->uuid('chat_room_uuid')->nullable()->after('chat_room_id');
            });
        }

        // Step 5: Copy UUID references to chat_messages using PHP loop (avoid SQL type mismatch)
        if (Schema::hasColumn('chat_messages', 'chat_room_uuid') && Schema::hasColumn('chat_messages', 'chat_room_id')) {
            // Build a mapping of old integer IDs to new UUIDs
            $roomMapping = DB::table('chat_rooms')
                ->select('id', 'uuid')
                ->get()
                ->pluck('uuid', 'id')
                ->toArray();

            // Update each message using the mapping
            $messages = DB::table('chat_messages')
                ->whereNull('chat_room_uuid')
                ->get(['id', 'chat_room_id']);

            foreach ($messages as $message) {
                if (isset($roomMapping[$message->chat_room_id])) {
                    DB::table('chat_messages')
                        ->where('id', $message->id)
                        ->update(['chat_room_uuid' => $roomMapping[$message->chat_room_id]]);
                }
            }
        }

        // Step 6: Drop old chat_room_id column and rename chat_room_uuid to chat_room_id
        // Only if both columns exist (chat_room_uuid hasn't been renamed yet)
        if (Schema::hasColumn('chat_messages', 'chat_room_uuid')) {
            if (Schema::hasColumn('chat_messages', 'chat_room_id')) {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->dropColumn('chat_room_id');
                });
            }

            Schema::table('chat_messages', function (Blueprint $table) {
                $table->renameColumn('chat_room_uuid', 'chat_room_id');
            });
        }

        // Step 7: Handle the primary key change for chat_rooms
        // Only if both id and uuid columns exist
        if (Schema::hasColumn('chat_rooms', 'id') && Schema::hasColumn('chat_rooms', 'uuid')) {
            // Check if id is the primary key with bigint type (meaning we haven't changed it yet)
            $isPrimaryKey = DB::select("
                SELECT COUNT(*) as cnt FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'chat_rooms' 
                AND COLUMN_NAME = 'id' 
                AND COLUMN_KEY = 'PRI'
                AND DATA_TYPE = 'bigint'
            ", [config('database.connections.mysql.database')]);

            if ($isPrimaryKey[0]->cnt > 0) {
                // First, remove AUTO_INCREMENT from the id column
                DB::statement('ALTER TABLE chat_rooms MODIFY id BIGINT UNSIGNED NOT NULL');

                // Now we can drop the primary key
                DB::statement('ALTER TABLE chat_rooms DROP PRIMARY KEY');

                // Drop the old id column
                Schema::table('chat_rooms', function (Blueprint $table) {
                    $table->dropColumn('id');
                });

                // Rename uuid to id
                Schema::table('chat_rooms', function (Blueprint $table) {
                    $table->renameColumn('uuid', 'id');
                });

                // Set UUID as primary key
                DB::statement('ALTER TABLE chat_rooms ADD PRIMARY KEY (id)');
            }
        }

        // Step 8: Re-add foreign key constraint with UUID
        // Check if it doesn't already exist
        if (Schema::hasTable('chat_messages') && Schema::hasColumn('chat_messages', 'chat_room_id')) {
            $foreignKeys = $this->listTableForeignKeys('chat_messages');
            if (!in_array('chat_messages_chat_room_id_foreign', $foreignKeys)) {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->foreign('chat_room_id')
                        ->references('id')
                        ->on('chat_rooms')
                        ->onDelete('cascade');
                });
            }
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
            $foreignKeys = $this->listTableForeignKeys('chat_messages');
            if (in_array('chat_messages_chat_room_id_foreign', $foreignKeys)) {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->dropForeign(['chat_room_id']);
                });
            }
        }
    }

    /**
     * Get list of foreign keys for a table using raw SQL (Laravel 11 compatible)
     */
    private function listTableForeignKeys(string $table): array
    {
        $database = config('database.connections.mysql.database');

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$database, $table]);

        return array_map(fn($fk) => $fk->CONSTRAINT_NAME, $foreignKeys);
    }
};
