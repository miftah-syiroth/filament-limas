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
            $table->foreignUuid('item_audit_id')->nullable()->after('item_id')->constrained('item_audits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_state_logs', function (Blueprint $table) {
            $table->dropForeign(['item_audit_id']);
            $table->dropColumn('item_audit_id');
        });
    }
};
