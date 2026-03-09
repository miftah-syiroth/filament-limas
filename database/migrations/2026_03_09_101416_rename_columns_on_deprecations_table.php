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
            // change deprecation_min to minimun_value
            $table->renameColumn('depreciation_min', 'minimun_value');
            $table->renameColumn('depreciation_type', 'method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deprecations', function (Blueprint $table) {
            // change minimun_value to depreciation_min
            $table->renameColumn('minimun_value', 'depreciation_min');
            $table->renameColumn('method', 'depreciation_type');
        });
    }
};
