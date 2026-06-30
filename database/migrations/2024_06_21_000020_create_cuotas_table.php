<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_plan_pago')->constrained('plan_pago');
            $table->integer('numero_cuota');
            $table->date('fecha_vencimiento');
            $table->double('monto')->default(0);
            $table->string('estado_cuota')->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->string('pagofacil_transaction_id')->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
