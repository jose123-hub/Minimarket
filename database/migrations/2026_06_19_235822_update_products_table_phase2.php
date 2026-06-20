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
    Schema::table('products', function (Blueprint $table) {
        $table->string('barcode')->nullable()->unique()->after('id');
        $table->integer('max_stock')->default(100)->after('min_stock');
        $table->foreignId('supplier_id')->nullable()->after('category_id')
              ->constrained('suppliers')->onDelete('set null');
    });
    }
    public function down(): void
    {
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['supplier_id']);
        $table->dropColumn(['barcode', 'max_stock', 'supplier_id']);
    });
    }
};
