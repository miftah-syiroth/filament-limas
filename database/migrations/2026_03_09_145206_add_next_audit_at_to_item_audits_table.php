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
        Schema::table('item_audits', function (Blueprint $table) {
            $table->dateTime('next_audit_at')->after('audited_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_audits', function (Blueprint $table) {
            $table->dropColumn('next_audit_at');
        });
    }
};
