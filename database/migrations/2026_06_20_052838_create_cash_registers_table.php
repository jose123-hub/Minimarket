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
    Schema::create('cash_registers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('location')->nullable();
        $table->string('status')->default('inactive'); // active, inactive
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('cash_registers');
    }
};
