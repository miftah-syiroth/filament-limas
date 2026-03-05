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
        Schema::create('item_state_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->constrained('items');
            $table->string('event_type', 50);

            $table->foreignUuid('from_location_id')->nullable();
            $table->foreignUuid('to_location_id')->nullable();
            $table->foreignUuid('from_department_id')->nullable();
            $table->foreignUuid('to_department_id')->nullable();
            $table->string('from_assignable_type')->nullable();
            $table->foreignUuid('from_assignable_id')->nullable();
            $table->string('to_assignable_type')->nullable();
            $table->foreignUuid('to_assignable_id')->nullable();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20)->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_state_logs');
    }
};
