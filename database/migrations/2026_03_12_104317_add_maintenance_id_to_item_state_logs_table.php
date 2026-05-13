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
            $table->foreignUuid('maintenance_id')->nullable()->constrained('maintenances');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_state_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('maintenance_id');
        });
    }
};
