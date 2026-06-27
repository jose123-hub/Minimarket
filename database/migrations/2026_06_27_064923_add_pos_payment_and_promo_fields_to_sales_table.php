<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('sales', 'promo_code')) {
                $table->string('promo_code')->nullable()->after('payment_reference');
            }

            if (!Schema::hasColumn('sales', 'cash_received')) {
                $table->decimal('cash_received', 10, 2)->nullable()->after('promo_code');
            }

            if (!Schema::hasColumn('sales', 'cash_change')) {
                $table->decimal('cash_change', 10, 2)->nullable()->after('cash_received');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'cash_change')) {
                $table->dropColumn('cash_change');
            }

            if (Schema::hasColumn('sales', 'cash_received')) {
                $table->dropColumn('cash_received');
            }

            if (Schema::hasColumn('sales', 'promo_code')) {
                $table->dropColumn('promo_code');
            }

            if (Schema::hasColumn('sales', 'payment_reference')) {
                $table->dropColumn('payment_reference');
            }
        });
    }
};