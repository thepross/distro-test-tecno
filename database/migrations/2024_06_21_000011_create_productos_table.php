<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('categoria')->nullable();
            $table->string('marca')->nullable();
            $table->string('unidad_medida')->nullable();
            $table->double('precio_compra')->default(0);
            $table->double('precio_venta')->default(0);
            $table->string('codigo_qr')->nullable();
            $table->integer('stock_minimo')->default(0);
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
