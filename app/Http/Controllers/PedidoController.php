<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PedidoController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(7);
        $clienteRole = Role::where('nombre', 'Cliente')->first();
        $clientes = Usuario::where('state', 'a')
            ->when($clienteRole, fn($q) => $q->where('id_rol', $clienteRole->id))
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Pedido/Index', [
            'pedidos' => Pedido::with(['cliente', 'detalles.producto', 'pagos', 'planPago.cuotas'])
                ->where('state', 'a')
                ->orderByDesc('id')
                ->get(),
            'clientes' => $clientes,
            'productos' => Producto::where('state', 'a')->orderBy('nombre')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validarPedido($request);

        DB::transaction(function () use ($data) {
            $pedido = Pedido::create([
                'fecha_pedido' => $data['fecha_pedido'],
                'id_cliente' => $data['id_cliente'] ?? null,
                'total' => 0,
                'estado_pedido' => $data['estado_pedido'],
                'observacion' => $data['observacion'] ?? null,
                'state' => 'a',
            ]);

            $total = $this->guardarDetalles($pedido, $data['detalles']);
            $pedido->update(['total' => $total]);
        });

        return to_route('pedido.index')->with('success', 'Pedido agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $this->validarPedido($request);

        DB::transaction(function () use ($data, $id) {
            $pedido = Pedido::findOrFail($id);
            $pedido->update([
                'fecha_pedido' => $data['fecha_pedido'],
                'id_cliente' => $data['id_cliente'] ?? null,
                'estado_pedido' => $data['estado_pedido'],
                'observacion' => $data['observacion'] ?? null,
            ]);

            DetallePedido::where('id_pedido', $pedido->id)->update(['state' => 'i']);

            $total = $this->guardarDetalles($pedido, $data['detalles']);
            $pedido->update(['total' => $total]);
        });

        return to_route('pedido.index')->with('success', 'Pedido actualizado exitosamente.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            Pedido::findOrFail($id)->update(['state' => 'i']);
            DetallePedido::where('id_pedido', $id)->update(['state' => 'i']);
        });

        return to_route('pedido.index')->with('success', 'Pedido eliminado exitosamente.');
    }

    private function validarPedido(Request $request): array
    {
        return $request->validate([
            'fecha_pedido' => 'required|date',
            'id_cliente' => 'nullable|exists:users,id',
            'estado_pedido' => 'required|string|max:100',
            'observacion' => 'nullable|string|max:255',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_venta' => 'required|numeric|min:0',
        ], [
            'detalles.required' => 'Debe agregar al menos un producto al pedido.',
            'detalles.min' => 'Debe agregar al menos un producto al pedido.',
        ]);
    }

    private function guardarDetalles(Pedido $pedido, array $detalles): float
    {
        $total = 0;

        foreach ($detalles as $detalle) {
            $cantidad = (int) $detalle['cantidad'];
            $precioVenta = (float) $detalle['precio_venta'];
            $subtotal = $cantidad * $precioVenta;
            $total += $subtotal;

            DetallePedido::create([
                'id_pedido' => $pedido->id,
                'id_producto' => $detalle['id_producto'],
                'cantidad' => $cantidad,
                'precio_venta' => $precioVenta,
                'subtotal' => $subtotal,
                'state' => 'a',
            ]);
        }

        return $total;
    }
}
