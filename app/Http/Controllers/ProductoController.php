<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(3);
        return Inertia::render('Producto/Index', [
            'productos' => Producto::where('state', 'a')->orderBy('id')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'unidad_medida' => 'nullable|string|max:100',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'codigo_qr' => 'nullable|string|max:255',
            'stock_minimo' => 'required|integer|min:0',
        ]);
        $data['state'] = 'a';
        Producto::create($data);
        return to_route('producto.index')->with('success', 'Producto agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'unidad_medida' => 'nullable|string|max:100',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'codigo_qr' => 'nullable|string|max:255',
            'stock_minimo' => 'required|integer|min:0',
        ]);
        Producto::findOrFail($id)->update($data);
        return to_route('producto.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->update(['state' => 'i']);
        return to_route('producto.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
