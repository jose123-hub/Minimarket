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
    Schema::create('discounts', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type'); // percentage, fixed
        $table->decimal('value', 10, 2);
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->string('condition')->nullable();
        $table->string('status')->default('active'); // active, inactive
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('discounts');
    }
};
