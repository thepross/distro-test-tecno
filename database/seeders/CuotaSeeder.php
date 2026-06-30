<?php

namespace Database\Seeders;

use App\Models\Cuota;
use App\Models\PlanPago;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CuotaSeeder extends Seeder
{
    public function run(): void
    {
        $plan = PlanPago::first();
        if (!$plan) return;
        for ($i = 1; $i <= $plan->cantidad_cuotas; $i++) {
            Cuota::updateOrCreate(
                ['id_plan_pago' => $plan->id, 'numero_cuota' => $i],
                [
                    'fecha_vencimiento' => Carbon::parse($plan->fecha_inicio)->addMonths($i - 1)->toDateString(),
                    'monto' => $plan->monto_cuota,
                    'estado_cuota' => $i === 1 ? 'pagado' : 'pendiente',
                    'fecha_pago' => $i === 1 ? now()->toDateString() : null,
                    'state' => 'a',
                ]
            );
        }
    }
}
