<?php

namespace Database\Seeders;

use App\Models\Entrega;
use App\Models\Pedido;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class EntregaSeeder extends Seeder
{
    public function run(): void
    {
        $repartidorRole = Role::where('nombre', 'Repartidor')->first();
        $repartidor = Usuario::where('id_rol', $repartidorRole?->id)->first();
        $pedido = Pedido::first();
        Entrega::updateOrCreate(['id_pedido' => $pedido->id], [
            'id_repartidor' => $repartidor?->id,
            'fecha_salida' => now()->toDateString(),
            'fecha_entrega' => null,
            'direccion_entrega' => 'Mercado principal',
            'estado_entrega' => 'en ruta',
            'observacion' => 'Entrega inicial de prueba',
            'state' => 'a',
        ]);
    }
}
