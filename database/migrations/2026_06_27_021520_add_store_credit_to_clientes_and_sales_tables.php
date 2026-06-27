<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'store_credit_balance')) {
                $table->decimal('store_credit_balance', 10, 2)
                    ->default(0)
                    ->after('star_progress_amount');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'store_credit_used')) {
                $table->decimal('store_credit_used', 10, 2)
                    ->default(0)
                    ->after('discount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'store_credit_balance')) {
                $table->dropColumn('store_credit_balance');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'store_credit_used')) {
                $table->dropColumn('store_credit_used');
            }
        });
    }
};
