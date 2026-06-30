<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\PlanPago;
use Illuminate\Database\Seeder;

class PlanPagoSeeder extends Seeder
{
    public function run(): void
    {
        $pedido = Pedido::first();
        PlanPago::updateOrCreate(['id_pedido' => $pedido->id], [
            'cantidad_cuotas' => 3,
            'monto_cuota' => round($pedido->total / 3, 2),
            'total_deuda' => $pedido->total,
            'saldo_pendiente' => max(0, $pedido->total - 20),
            'fecha_inicio' => now()->toDateString(),
            'estado_plan' => 'en curso',
            'state' => 'a',
        ]);
    }
}
