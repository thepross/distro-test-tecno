<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('privilegios', function (Blueprint $table) {
            $table->id();
            $table->string('funcionalidad');
            $table->foreignId('id_rol')->constrained('roles');
            $table->boolean('agregar')->default(false);
            $table->boolean('borrar')->default(false);
            $table->boolean('modificar')->default(false);
            $table->boolean('leer')->default(false);
            $table->char('state', 1)->default('a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('privilegios');
    }
};
