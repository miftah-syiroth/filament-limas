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
        Schema::table('item_state_logs', function (Blueprint $table) {
            $table->foreignUuid('from_room_id')->nullable()->constrained('rooms');
            $table->foreignUuid('to_room_id')->nullable()->constrained('rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_state_logs', function (Blueprint $table) {
            $table->dropForeign(['from_room_id']);
            $table->dropForeign(['to_room_id']);
            $table->dropColumn('from_room_id');
            $table->dropColumn('to_room_id');
        });
    }
};
