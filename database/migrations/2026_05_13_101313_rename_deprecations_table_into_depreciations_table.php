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
        if (Schema::hasColumn('models', 'deprecation_id')) {
            Schema::table('models', function (Blueprint $table) {
                $table->renameColumn('deprecation_id', 'depreciation_id');
            });
        }

        Schema::rename('deprecations', 'depreciations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('models', 'depreciation_id')) {
            Schema::table('models', function (Blueprint $table) {
                $table->renameColumn('depreciation_id', 'deprecation_id');
            });
        }

        Schema::rename('depreciations', 'deprecations');
    }
};
