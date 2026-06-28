<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'rounding_adjustment')) {
                $table->decimal('rounding_adjustment', 10, 2)
                    ->default(0)
                    ->after('discount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'rounding_adjustment')) {
                $table->dropColumn('rounding_adjustment');
            }
        });
    }
};