<?php

namespace Database\Seeders;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        $pedido = Pedido::first();
        Pago::updateOrCreate(['observacion' => 'Pago inicial de prueba'], [
            'id_pedido' => $pedido?->id,
            'fecha_pago' => now()->toDateString(),
            'monto' => 20,
            'tipo_pago' => 'efectivo',
            'estado_pago' => 'parcial',
            'observacion' => 'Pago inicial de prueba',
            'state' => 'a',
        ]);
    }
}
