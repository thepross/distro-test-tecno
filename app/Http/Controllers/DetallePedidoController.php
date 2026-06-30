<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DetallePedidoController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(8);
        return Inertia::render('DetallePedido/Index', [
            'detalle_pedidos' => DetallePedido::with(['pedido','producto'])->where('state', 'a')->orderByDesc('id')->get(),
            'pedidos' => Pedido::where('state', 'a')->orderByDesc('id')->get(),
            'productos' => Producto::where('state', 'a')->orderBy('nombre')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id',
            'id_producto' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_venta' => 'required|numeric|min:0',
        ]);
        $data['subtotal'] = $data['cantidad'] * $data['precio_venta'];
        $data['state'] = 'a';
        DetallePedido::create($data);
        $this->actualizarTotalPedido($data['id_pedido']);
        $this->registrarSalidaInventario($data);
        return to_route('detalle_pedido.index')->with('success', 'Detalle de pedido agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $detalle = DetallePedido::findOrFail($id);
        $data = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id',
            'id_producto' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_venta' => 'required|numeric|min:0',
        ]);
        $data['subtotal'] = $data['cantidad'] * $data['precio_venta'];
        $oldPedido = $detalle->id_pedido;
        $detalle->update($data);
        $this->actualizarTotalPedido($oldPedido);
        $this->actualizarTotalPedido($data['id_pedido']);
        return to_route('detalle_pedido.index')->with('success', 'Detalle de pedido actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $detalle = DetallePedido::findOrFail($id);
        $pedidoId = $detalle->id_pedido;
        $detalle->update(['state' => 'i']);
        $this->actualizarTotalPedido($pedidoId);
        return to_route('detalle_pedido.index')->with('success', 'Detalle de pedido eliminado exitosamente.');
    }

    private function actualizarTotalPedido($pedidoId): void
    {
        $total = DetallePedido::where('id_pedido', $pedidoId)->where('state', 'a')->sum('subtotal');
        Pedido::where('id', $pedidoId)->update(['total' => $total]);
    }

    private function registrarSalidaInventario(array $data): void
    {
        $stockAnterior = Inventario::where('id_producto', $data['id_producto'])->where('state','a')->orderByDesc('id')->value('stock_actual') ?? 0;
        Inventario::create([
            'id_producto' => $data['id_producto'],
            'tipo_movimiento' => 'salida',
            'cantidad' => $data['cantidad'],
            'fecha_movimiento' => now()->toDateString(),
            'stock_actual' => max(0, $stockAnterior - $data['cantidad']),
            'descripcion' => 'Salida por pedido',
            'state' => 'a',
        ]);
    }
}
