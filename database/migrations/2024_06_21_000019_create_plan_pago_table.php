<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos');
            $table->integer('cantidad_cuotas')->default(1);
            $table->double('monto_cuota')->default(0);
            $table->double('total_deuda')->default(0);
            $table->double('saldo_pendiente')->default(0);
            $table->date('fecha_inicio');
            $table->string('estado_plan')->default('pendiente');
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_pago');
    }
};
