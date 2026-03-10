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
        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('status', 20)->nullable()->after('completed_at')->comment('reported, in_progress, completed, cancelled');
            $table->foreignUuid('item_audit_id')->nullable()->constrained('item_audits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropForeign(['item_audit_id']);
            $table->dropColumn('item_audit_id');
        });
    }
};
