<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {

            if (!Schema::hasColumn('sales', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->unique()->after('total');
            }

            if (!Schema::hasColumn('sales', 'delivery_type')) {
                $table->string('delivery_type')->default('delivery')->after('receipt_number');
            }

            if (!Schema::hasColumn('sales', 'delivery_address')) {
                $table->string('delivery_address')->nullable()->after('delivery_type');
            }

            if (!Schema::hasColumn('sales', 'delivery_reference')) {
                $table->string('delivery_reference')->nullable()->after('delivery_address');
            }

            if (!Schema::hasColumn('sales', 'delivery_phone')) {
                $table->string('delivery_phone', 20)->nullable()->after('delivery_reference');
            }

            if (!Schema::hasColumn('sales', 'pickup_store')) {
                $table->string('pickup_store')->nullable()->after('delivery_phone');
            }

            if (!Schema::hasColumn('sales', 'pickup_note')) {
                $table->string('pickup_note')->nullable()->after('pickup_store');
            }

            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method')->default('card')->after('pickup_note');
            }

            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->string('payment_status')->default('paid')->after('payment_method');
            }

            if (!Schema::hasColumn('sales', 'card_last_four')) {
                $table->string('card_last_four', 4)->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('sales', 'order_status')) {
                $table->string('order_status')->default('pending')->after('card_last_four');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'order_status')) {
                $table->dropColumn('order_status');
            }

            if (Schema::hasColumn('sales', 'card_last_four')) {
                $table->dropColumn('card_last_four');
            }

            if (Schema::hasColumn('sales', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            if (Schema::hasColumn('sales', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            if (Schema::hasColumn('sales', 'pickup_note')) {
                $table->dropColumn('pickup_note');
            }

            if (Schema::hasColumn('sales', 'pickup_store')) {
                $table->dropColumn('pickup_store');
            }

            if (Schema::hasColumn('sales', 'delivery_phone')) {
                $table->dropColumn('delivery_phone');
            }

            if (Schema::hasColumn('sales', 'delivery_reference')) {
                $table->dropColumn('delivery_reference');
            }

            if (Schema::hasColumn('sales', 'delivery_address')) {
                $table->dropColumn('delivery_address');
            }

            if (Schema::hasColumn('sales', 'delivery_type')) {
                $table->dropColumn('delivery_type');
            }

            if (Schema::hasColumn('sales', 'receipt_number')) {
                $table->dropColumn('receipt_number');
            }
        });
    }
};