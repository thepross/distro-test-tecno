<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Cuota;
use App\Models\PlanPago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CuotaController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(12);
        return Inertia::render('Cuota/Index', [
            'cuotas' => Cuota::with('planPago')->where('state', 'a')->orderByDesc('id')->get(),
            'planes' => PlanPago::where('state', 'a')->orderByDesc('id')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_plan_pago' => 'required|exists:plan_pago,id',
            'numero_cuota' => 'required|integer|min:1',
            'fecha_vencimiento' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'estado_cuota' => 'required|string|max:100',
            'fecha_pago' => 'nullable|date',
        ]);
        $data['state'] = 'a';
        Cuota::create($data);
        return to_route('cuota.index')->with('success', 'Cuota agregada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_plan_pago' => 'required|exists:plan_pago,id',
            'numero_cuota' => 'required|integer|min:1',
            'fecha_vencimiento' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'estado_cuota' => 'required|string|max:100',
            'fecha_pago' => 'nullable|date',
        ]);
        Cuota::findOrFail($id)->update($data);
        return to_route('cuota.index')->with('success', 'Cuota actualizada exitosamente.');
    }

    public function destroy($id)
    {
        Cuota::findOrFail($id)->update(['state' => 'i']);
        return to_route('cuota.index')->with('success', 'Cuota eliminada exitosamente.');
    }
}
