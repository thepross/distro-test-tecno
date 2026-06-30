<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_compra')->constrained('compras');
            $table->foreignId('id_producto')->constrained('productos');
            $table->integer('cantidad')->default(1);
            $table->double('precio_compra')->default(0);
            $table->double('subtotal')->default(0);
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_compra');
    }
};
