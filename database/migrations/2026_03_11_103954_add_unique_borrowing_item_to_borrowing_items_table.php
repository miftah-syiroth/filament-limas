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
        Schema::table('borrowing_items', function (Blueprint $table) {
            $table->unique(['borrowing_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowing_items', function (Blueprint $table) {
            $table->dropUnique(['borrowing_id', 'item_id']);
        });
    }
};
