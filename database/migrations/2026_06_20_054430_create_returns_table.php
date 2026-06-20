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
    Schema::create('returns', function (Blueprint $table) {
        $table->id();
        $table->datetime('return_date');
        $table->string('reason');
        $table->decimal('amount_returned', 10, 2)->default(0);
        $table->string('status')->default('pending'); // pending, approved, rejected
        $table->foreignId('sale_id')->constrained('sales')->onDelete('restrict');
        $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('returns');
    }
};
