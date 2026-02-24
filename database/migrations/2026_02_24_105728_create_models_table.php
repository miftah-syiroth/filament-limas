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
        Schema::create('models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('model_number')->nullable();

            // jumlah minimal yang harus ada di inventory untuk pembatasan stock, peminjaman dan alert
            $table->integer('min_amount')->nullable();

            // jumlah bulan sampai model ini dianggap sudah habis umur pakainya
            $table->integer('end_of_life')->nullable();

            $table->boolean('require_serial_number')->default(false);
            $table->foreignUuid('manufacture_id')->nullable()->constrained('manufactures');
            $table->foreignUuid('category_id')->nullable()->constrained('categories');
            $table->foreignUuid('deprecation_id')->nullable()->constrained('deprecations');
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
        Schema::dropIfExists('models');
    }
};
