<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_pedido');
            $table->foreignId('id_cliente')->nullable()->constrained('users');
            $table->double('total')->default(0);
            $table->string('estado_pedido')->default('pendiente');
            $table->string('observacion')->nullable();
            $table->string('pagofacil_transaction_id')->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
