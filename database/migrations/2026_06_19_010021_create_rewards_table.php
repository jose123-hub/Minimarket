<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('rewards', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('description')->nullable();
        $table->string('type'); 
        $table->integer('stars_required');
        $table->decimal('discount_value', 8, 2)->default(0);
        $table->integer('available_stock')->default(0);
        $table->string('status')->default('active'); 
        $table->datetime('start_date')->nullable();
        $table->datetime('end_date')->nullable();
        $table->timestamps();
    });
    }

    public function down(): void
    { 
    Schema::dropIfExists('rewards');
    }
};
