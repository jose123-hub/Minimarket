<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'star_progress_amount')) {
                $table->decimal('star_progress_amount', 10, 2)
                    ->default(0)
                    ->after('accumulated_stars');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'stars_earned')) {
                $table->integer('stars_earned')
                    ->default(0)
                    ->after('total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'star_progress_amount')) {
                $table->dropColumn('star_progress_amount');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'stars_earned')) {
                $table->dropColumn('stars_earned');
            }
        });
    }
};