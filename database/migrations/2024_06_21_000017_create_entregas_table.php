<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos');
            $table->foreignId('id_repartidor')->nullable()->constrained('users');
            $table->date('fecha_salida')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->string('direccion_entrega')->nullable();
            $table->string('estado_entrega')->default('pendiente');
            $table->string('observacion')->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
