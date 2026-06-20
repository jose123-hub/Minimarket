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
    Schema::create('report_logs', function (Blueprint $table) {
        $table->id();
        $table->string('report_type'); // sales, inventory, purchases, loyalty
        $table->string('parameters')->nullable();
        $table->datetime('generated_at');
        $table->string('format')->default('pdf'); // pdf, excel, csv
        $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
        $table->timestamps();
    });
    }
    public function down(): void
    {
    Schema::dropIfExists('report_logs');
    }
};
