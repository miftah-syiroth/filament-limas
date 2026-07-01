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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable()->change();
            $table->foreignUuid('to_location_id')->nullable()->after('user_id');
            $table->foreignUuid('to_department_id')->nullable()->after('to_location_id');
            $table->foreignUuid('to_room_id')->nullable()->after('to_department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable(false)->change();
            $table->dropColumn('to_location_id');
            $table->dropColumn('to_department_id');
            $table->dropColumn('to_room_id');
        });
    }
};
