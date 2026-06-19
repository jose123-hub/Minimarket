<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('clientes', function (Blueprint $table) {
    $table->id('id_cliente');
    $table->string('first_name');
    $table->string('last_name')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('address')->nullable();
    $table->string('type')->default('regular');
    $table->integer('accumulated_stars')->default(0);
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
    });
    }

    public function down(): void
    {
    Schema::dropIfExists('clientes');
    }
};
