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
    Schema::create('return_details', function (Blueprint $table) {
        $table->id();
        $table->integer('quantity');
        $table->decimal('unit_price', 10, 2);
        $table->decimal('subtotal', 10, 2);
        $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('return_details');
    }
};
