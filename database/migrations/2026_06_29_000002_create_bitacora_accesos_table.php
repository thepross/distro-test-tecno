<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('evento', 50);
            $table->string('estado', 30)->default('registrado');
            $table->string('ip', 60)->nullable();
            $table->string('metodo', 20)->nullable();
            $table->string('ruta')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('descripcion')->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();

            $table->index(['evento', 'created_at']);
            $table->index(['id_usuario', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_accesos');
    }
};
