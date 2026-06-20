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
    Schema::create('inventory_movements', function (Blueprint $table) {
        $table->id();
        $table->string('movement_type'); // in, out, adjustment
        $table->integer('quantity');
        $table->decimal('unit_price', 10, 2)->nullable();
        $table->string('reason')->nullable();
        $table->datetime('date');
        $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
        $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
        $table->integer('reference_id')->nullable();
        $table->string('reference_table')->nullable();
        $table->timestamps();
    });
    } 
    public function down(): void
    {
    Schema::dropIfExists('inventory_movements');
    }

};
