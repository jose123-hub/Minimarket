<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('star_history', function (Blueprint $table) {
        $table->id();
        $table->string('movement_type'); // earned, redeemed
        $table->integer('amount');
        $table->string('reason')->nullable();
        $table->datetime('date');
        $table->foreignId('client_id')->constrained('clientes', 'id_cliente')->onDelete('cascade');
        $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null');
        $table->foreignId('redemption_id')->nullable()->constrained('reward_redemptions')->onDelete('set null');
        $table->timestamps();
    });
    }

    public function down(): void
    {
    Schema::dropIfExists('star_history');
    }
};
