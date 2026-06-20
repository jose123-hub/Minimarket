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
    Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique();
        $table->date('order_date');
        $table->date('estimated_delivery')->nullable();
        $table->date('actual_delivery')->nullable();
        $table->decimal('total', 10, 2)->default(0);
        $table->string('status')->default('pending'); // pending, received, cancelled
        $table->text('notes')->nullable();
        $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
        $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('purchase_orders');
    }
};
