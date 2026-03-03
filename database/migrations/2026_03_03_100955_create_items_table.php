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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('model_id')->constrained('models');
            $table->foreignUuid('location_id')->constrained('locations');
            $table->foreignUuid('department_id')->nullable()->constrained('departments');
            $table->foreignUuid('supplier_id')->nullable()->constrained('suppliers');

            // morph to employee, user, students, lecturer, etc
            $table->foreignUuid('assignable_id')->nullable();
            $table->string('assignable_type')->nullable();

            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->string('serial_number')->unique();

            $table->integer('order_quantity');
            $table->dateTime('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->dateTime('eol_date')->nullable();
            $table->integer('warranty_months')->nullable();

            $table->boolean('is_individual_tracking')->default(true);
            $table->string('status', 20);
            $table->text('notes')->nullable();

            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
