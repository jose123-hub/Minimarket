<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'cashier_id')) {
                $table->unsignedBigInteger('cashier_id')->nullable()->change();
            }

            if (Schema::hasColumn('sales', 'cash_opening_id')) {
                $table->unsignedBigInteger('cash_opening_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'cashier_id')) {
                $table->unsignedBigInteger('cashier_id')->nullable(false)->change();
            }

            if (Schema::hasColumn('sales', 'cash_opening_id')) {
                $table->unsignedBigInteger('cash_opening_id')->nullable(false)->change();
            }
        });
    }
};
