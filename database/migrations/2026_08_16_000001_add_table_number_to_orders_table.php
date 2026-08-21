<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'table_number')) {
                $table->string('table_number', 20)->nullable()->after('order_code');
            }
        });

        DB::table('orders')
            ->whereNull('table_number')
            ->whereNotNull('order_code')
            ->update([
                'table_number' => DB::raw('order_code'),
            ]);

        Schema::table('orders', function (Blueprint $table) {
            $table->string('table_number', 20)->nullable(false)->change();
            $table->index('table_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['table_number']);
            $table->dropColumn('table_number');
        });
    }
};
