<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        $clienteRole = Role::where('nombre', 'Cliente')->first();
        $cliente = Usuario::where('id_rol', $clienteRole?->id)->first();
        Pedido::updateOrCreate(['observacion' => 'Pedido inicial de prueba'], [
            'fecha_pedido' => now()->toDateString(),
            'id_cliente' => $cliente?->id,
            'total' => 0,
            'estado_pedido' => 'pendiente',
            'observacion' => 'Pedido inicial de prueba',
            'state' => 'a',
        ]);
    }
}
