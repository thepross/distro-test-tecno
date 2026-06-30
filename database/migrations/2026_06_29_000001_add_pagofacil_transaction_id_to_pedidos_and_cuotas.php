<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pedidos') && !Schema::hasColumn('pedidos', 'pagofacil_transaction_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->string('pagofacil_transaction_id')->nullable()->after('observacion');
            });
        }

        if (Schema::hasTable('cuotas') && !Schema::hasColumn('cuotas', 'pagofacil_transaction_id')) {
            Schema::table('cuotas', function (Blueprint $table) {
                $table->string('pagofacil_transaction_id')->nullable()->after('fecha_pago');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pedidos') && Schema::hasColumn('pedidos', 'pagofacil_transaction_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropColumn('pagofacil_transaction_id');
            });
        }

        if (Schema::hasTable('cuotas') && Schema::hasColumn('cuotas', 'pagofacil_transaction_id')) {
            Schema::table('cuotas', function (Blueprint $table) {
                $table->dropColumn('pagofacil_transaction_id');
            });
        }
    }
};
