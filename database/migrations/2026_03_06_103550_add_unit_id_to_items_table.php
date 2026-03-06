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
        Schema::table('items', function (Blueprint $table) {
            // drop column unit_name
            $table->dropColumn('unit_name');

            $table->foreignUuid('unit_id')->nullable()->constrained('units');
        });

        // table stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn('unit_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // table stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->string('unit_name', 20)->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');

            // add column unit_name
            $table->string('unit_name', 20)->nullable();
        });
    }
};
