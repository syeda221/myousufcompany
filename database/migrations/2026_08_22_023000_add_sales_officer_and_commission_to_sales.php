<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'salesman_name')) {
                $table->string('salesman_name')->nullable()->after('walkin_name');
            }
            if (!Schema::hasColumn('sales', 'commission_type')) {
                $table->string('commission_type', 20)->default('percent')->after('salesman_name');
            }
            if (!Schema::hasColumn('sales', 'commission_rate')) {
                $table->decimal('commission_rate', 12, 2)->default(0)->after('commission_type');
            }
            if (!Schema::hasColumn('sales', 'commission_amount')) {
                $table->decimal('commission_amount', 12, 2)->default(0)->after('commission_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'commission_amount')) {
                $table->dropColumn('commission_amount');
            }
            if (Schema::hasColumn('sales', 'commission_rate')) {
                $table->dropColumn('commission_rate');
            }
            if (Schema::hasColumn('sales', 'commission_type')) {
                $table->dropColumn('commission_type');
            }
            if (Schema::hasColumn('sales', 'salesman_name')) {
                $table->dropColumn('salesman_name');
            }
        });
    }
};