<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(10);
        return Inertia::render('Pago/Index', [
            'pagos' => Pago::with('pedido')->where('state', 'a')->orderByDesc('id')->get(),
            'pedidos' => Pedido::where('state', 'a')->orderByDesc('id')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request, $pedidoId = null)
    {
        $data = $request->validate([
            'id_pedido' => 'nullable|exists:pedidos,id',
            'fecha_pago' => 'nullable|date',
            'monto' => 'required|numeric|min:0.01',
            'tipo_pago' => 'required|string|max:100',
            'estado_pago' => 'nullable|string|max:100',
            'observacion' => 'nullable|string|max:255',
        ]);

        $data['id_pedido'] = $pedidoId ?: ($data['id_pedido'] ?? null);
        $data['fecha_pago'] = $data['fecha_pago'] ?? now()->toDateString();
        $data['estado_pago'] = $data['estado_pago'] ?? 'pagado';
        $data['state'] = 'a';

        Pago::create($data);
        $this->actualizarEstadoPedido($data['id_pedido']);

        return to_route('pedido.index')->with('success', 'Pago registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $data = $request->validate([
            'id_pedido' => 'nullable|exists:pedidos,id',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'tipo_pago' => 'nullable|string|max:100',
            'estado_pago' => 'required|string|max:100',
            'observacion' => 'nullable|string|max:255',
        ]);

        $oldPedido = $pago->id_pedido;
        $pago->update($data);
        $this->actualizarEstadoPedido($oldPedido);
        $this->actualizarEstadoPedido($data['id_pedido'] ?? null);

        return to_route('pago.index')->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pedidoId = $pago->id_pedido;
        $pago->update(['state' => 'i']);
        $this->actualizarEstadoPedido($pedidoId);

        return to_route('pago.index')->with('success', 'Pago eliminado exitosamente.');
    }

    public function actualizarEstadoPedido($pedidoId): void
    {
        if (!$pedidoId) {
            return;
        }

        $pedido = Pedido::find($pedidoId);
        if (!$pedido) {
            return;
        }

        $pagado = Pago::where('id_pedido', $pedidoId)
            ->where('state', 'a')
            ->where('estado_pago', 'pagado')
            ->sum('monto');

        if ($pedido->total > 0 && $pagado >= $pedido->total) {
            $pedido->update(['estado_pedido' => 'pagado']);
        } elseif ($pagado > 0) {
            $pedido->update(['estado_pedido' => 'pago parcial']);
        } elseif ($pedido->estado_pedido === 'pagado' || $pedido->estado_pedido === 'pago parcial') {
            $pedido->update(['estado_pedido' => 'pendiente']);
        }
    }
}
