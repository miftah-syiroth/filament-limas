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
        Schema::create('borrowing_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('borrowing_id')->constrained('borrowings');
            $table->foreignUuid('item_id')->constrained('items');
            $table->integer('quantity');
            $table->dateTime('checked_out_at');
            $table->dateTime('checked_in_at')->nullable();
            $table->string('condition_in', 20)->comment('excellent, good, fair, poor, unusable');
            $table->string('condition_out', 20)->comment('excellent, good, fair, poor, unusable');
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
        Schema::dropIfExists('borrowing_items');
    }
};
