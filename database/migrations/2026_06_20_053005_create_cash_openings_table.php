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
    Schema::create('cash_openings', function (Blueprint $table) {
        $table->id();
        $table->datetime('opening_date');
        $table->datetime('closing_date')->nullable();
        $table->decimal('initial_amount', 10, 2)->default(0);
        $table->decimal('final_amount', 10, 2)->nullable();
        $table->decimal('total_sales', 10, 2)->default(0);
        $table->decimal('difference', 10, 2)->nullable();
        $table->string('status')->default('open'); // open, closed
        $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('restrict');
        $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('cash_openings');
    }
};
