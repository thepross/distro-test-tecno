<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Contador;
use App\Models\DetalleCompra;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DetalleCompraController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(5);
        return Inertia::render('DetalleCompra/Index', [
            'detalle_compras' => DetalleCompra::with(['compra','producto'])->where('state', 'a')->orderByDesc('id')->get(),
            'compras' => Compra::where('state', 'a')->orderByDesc('id')->get(),
            'productos' => Producto::where('state', 'a')->orderBy('nombre')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_compra' => 'required|exists:compras,id',
            'id_producto' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_compra' => 'required|numeric|min:0',
        ]);
        $data['subtotal'] = $data['cantidad'] * $data['precio_compra'];
        $data['state'] = 'a';
        DetalleCompra::create($data);
        $this->actualizarTotalCompra($data['id_compra']);
        $this->registrarEntradaInventario($data);
        return to_route('detalle_compra.index')->with('success', 'Detalle de compra agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $detalle = DetalleCompra::findOrFail($id);
        $data = $request->validate([
            'id_compra' => 'required|exists:compras,id',
            'id_producto' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_compra' => 'required|numeric|min:0',
        ]);
        $data['subtotal'] = $data['cantidad'] * $data['precio_compra'];
        $oldCompra = $detalle->id_compra;
        $detalle->update($data);
        $this->actualizarTotalCompra($oldCompra);
        $this->actualizarTotalCompra($data['id_compra']);
        return to_route('detalle_compra.index')->with('success', 'Detalle de compra actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $detalle = DetalleCompra::findOrFail($id);
        $compraId = $detalle->id_compra;
        $detalle->update(['state' => 'i']);
        $this->actualizarTotalCompra($compraId);
        return to_route('detalle_compra.index')->with('success', 'Detalle de compra eliminado exitosamente.');
    }

    private function actualizarTotalCompra($compraId): void
    {
        $total = DetalleCompra::where('id_compra', $compraId)->where('state', 'a')->sum('subtotal');
        Compra::where('id', $compraId)->update(['total' => $total]);
    }

    private function registrarEntradaInventario(array $data): void
    {
        $stockAnterior = Inventario::where('id_producto', $data['id_producto'])->where('state','a')->orderByDesc('id')->value('stock_actual') ?? 0;
        Inventario::create([
            'id_producto' => $data['id_producto'],
            'tipo_movimiento' => 'entrada',
            'cantidad' => $data['cantidad'],
            'fecha_movimiento' => now()->toDateString(),
            'stock_actual' => $stockAnterior + $data['cantidad'],
            'descripcion' => 'Entrada por compra',
            'state' => 'a',
        ]);
    }
}
