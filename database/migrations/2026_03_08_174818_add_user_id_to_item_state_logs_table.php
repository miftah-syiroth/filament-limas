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
            $table->foreignUuid('from_user_id')->nullable()->constrained('users');
            $table->foreignUuid('to_user_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_state_logs', function (Blueprint $table) {
            $table->dropForeign(['from_user_id', 'to_user_id']);
            $table->dropColumn(['from_user_id', 'to_user_id']);
        });
    }
};
