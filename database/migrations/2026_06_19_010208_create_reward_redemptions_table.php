<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('reward_redemptions', function (Blueprint $table) {
        $table->id();
        $table->datetime('redemption_date');
        $table->integer('stars_used');
        $table->string('status')->default('pending'); // pending, completed, cancelled
        $table->foreignId('client_id')->constrained('clientes', 'id_cliente')->onDelete('cascade');
        $table->foreignId('reward_id')->constrained('rewards')->onDelete('cascade');
        $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null');
        $table->timestamps();
    });
    }

    public function down(): void
   {
    Schema::dropIfExists('reward_redemptions');
    }
};
