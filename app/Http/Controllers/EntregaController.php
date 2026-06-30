<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Entrega;
use App\Models\Pedido;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntregaController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(9);
        $repartidorRole = Role::where('nombre', 'Repartidor')->first();
        $repartidores = Usuario::where('state', 'a')
            ->when($repartidorRole, fn($q) => $q->where('id_rol', $repartidorRole->id))
            ->orderBy('nombre')->get();

        return Inertia::render('Entrega/Index', [
            'entregas' => Entrega::with(['pedido','repartidor'])->where('state', 'a')->orderByDesc('id')->get(),
            'pedidos' => Pedido::where('state', 'a')->orderByDesc('id')->get(),
            'repartidores' => $repartidores,
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id',
            'id_repartidor' => 'nullable|exists:users,id',
            'fecha_salida' => 'nullable|date',
            'fecha_entrega' => 'nullable|date',
            'direccion_entrega' => 'nullable|string|max:255',
            'estado_entrega' => 'required|string|max:100',
            'observacion' => 'nullable|string|max:255',
        ]);
        $data['state'] = 'a';
        Entrega::create($data);
        return to_route('entrega.index')->with('success', 'Entrega agregada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id',
            'id_repartidor' => 'nullable|exists:users,id',
            'fecha_salida' => 'nullable|date',
            'fecha_entrega' => 'nullable|date',
            'direccion_entrega' => 'nullable|string|max:255',
            'estado_entrega' => 'required|string|max:100',
            'observacion' => 'nullable|string|max:255',
        ]);
        Entrega::findOrFail($id)->update($data);
        return to_route('entrega.index')->with('success', 'Entrega actualizada exitosamente.');
    }

    public function destroy($id)
    {
        Entrega::findOrFail($id)->update(['state' => 'i']);
        return to_route('entrega.index')->with('success', 'Entrega eliminada exitosamente.');
    }
}
