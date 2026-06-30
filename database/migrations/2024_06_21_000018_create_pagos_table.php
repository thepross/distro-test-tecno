<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->nullable()->constrained('pedidos');
            $table->date('fecha_pago');
            $table->double('monto')->default(0);
            $table->string('tipo_pago')->nullable();
            $table->string('estado_pago')->default('pendiente');
            $table->string('observacion')->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
