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
    Schema::table('sales', function (Blueprint $table) {
        $table->string('invoice_number')->nullable()->unique()->after('id');
        $table->decimal('discount', 10, 2)->default(0)->after('total');
        $table->decimal('tax', 10, 2)->default(0)->after('discount');
        $table->string('status')->default('completed')->after('tax');
        $table->string('payment_method')->default('cash')->after('status');
        $table->string('voucher_type')->default('receipt')->after('payment_method');
        $table->foreignId('cash_opening_id')->nullable()->after('cashier_id')
              ->constrained('cash_openings')->onDelete('set null');
    });
    }
    public function down(): void
    {
    Schema::table('sales', function (Blueprint $table) {
        $table->dropForeign(['cash_opening_id']);
        $table->dropColumn(['invoice_number', 'discount', 'tax', 'status', 'payment_method', 'voucher_type', 'cash_opening_id']);
    });
    }
};
