<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventarioController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(6);
        return Inertia::render('Inventario/Index', [
            'inventarios' => Inventario::with('producto')->where('state', 'a')->orderByDesc('id')->get(),
            'productos' => Producto::where('state', 'a')->orderBy('nombre')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_producto' => 'required|exists:productos,id',
            'tipo_movimiento' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'fecha_movimiento' => 'required|date',
            'stock_actual' => 'required|integer|min:0',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $data['state'] = 'a';
        Inventario::create($data);
        return to_route('inventario.index')->with('success', 'Movimiento de inventario agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_producto' => 'required|exists:productos,id',
            'tipo_movimiento' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'fecha_movimiento' => 'required|date',
            'stock_actual' => 'required|integer|min:0',
            'descripcion' => 'nullable|string|max:255',
        ]);
        Inventario::findOrFail($id)->update($data);
        return to_route('inventario.index')->with('success', 'Movimiento de inventario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Inventario::findOrFail($id)->update(['state' => 'i']);
        return to_route('inventario.index')->with('success', 'Movimiento de inventario eliminado exitosamente.');
    }
}
