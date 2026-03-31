<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE categories ALTER COLUMN name TYPE CITEXT');
        DB::statement('ALTER TABLE units ALTER COLUMN name TYPE CITEXT');
        DB::statement('ALTER TABLE manufactures ALTER COLUMN name TYPE CITEXT');
        DB::statement('ALTER TABLE suppliers ALTER COLUMN name TYPE CITEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE categories ALTER COLUMN name TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE units ALTER COLUMN name TYPE VARCHAR(20)');
        DB::statement('ALTER TABLE manufactures ALTER COLUMN name TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE suppliers ALTER COLUMN name TYPE VARCHAR(255)');
    }
};
