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
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::rename('companies', 'organizations');

        Schema::table('departments', function (Blueprint $table) {
            $table->renameColumn('company_id', 'organization_id');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('company_id', 'organization_id');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->renameColumn('organization_id', 'company_id');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('organization_id', 'company_id');
        });

        Schema::rename('organizations', 'companies');

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });
    }
};
