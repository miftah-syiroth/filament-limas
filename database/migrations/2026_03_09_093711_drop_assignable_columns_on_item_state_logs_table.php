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
            $table->dropColumn(['from_assignable_id', 'from_assignable_type', 'to_assignable_id', 'to_assignable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_state_logs', function (Blueprint $table) {
            $table->foreignUuid('from_assignable_id')->nullable();
            $table->string('from_assignable_type')->nullable();
            $table->foreignUuid('to_assignable_id')->nullable();
            $table->string('to_assignable_type')->nullable();
        });
    }
};
