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
        Schema::table('deprecations', function (Blueprint $table) {
            // change minimun_value to minimum
            $table->renameColumn('minimun_value', 'minimum_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deprecations', function (Blueprint $table) {
            // change minimum to minimun_value
            $table->renameColumn('minimum_value', 'minimun_value');
        });
    }
};
