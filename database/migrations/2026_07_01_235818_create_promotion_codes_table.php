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
    Schema::create('promotion_codes', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->enum('payment_method', ['all', 'cash', 'card', 'yape', 'plin'])->default('all');
        $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
        $table->decimal('value', 10, 2);
        $table->decimal('minimum_amount', 10, 2)->default(0);
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->unsignedInteger('usage_limit')->nullable();
        $table->unsignedInteger('used_count')->default(0);
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_codes');
    }
};
