<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoFacilWebHookController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Webhook PagoFácil recibido:', $request->all());

        $data = $request->all();
        $pedidoID = $data['PedidoID'] ?? $data['pedidoID'] ?? $data['pedidoId'] ?? $data['paymentNumber'] ?? null;
        $estado = (int) ($data['Estado'] ?? $data['estado'] ?? $data['status'] ?? 2);
        $transaccion = $data['TransactionId'] ?? $data['transactionId'] ?? $data['Transaccion'] ?? null;

        if (!$pedidoID) {
            return response()->json([
                'error' => 1,
                'status' => 0,
                'message' => 'No se recibió el identificador del pedido.',
            ], 422);
        }

        if ($estado !== 2) {
            return response()->json([
                'error' => 0,
                'status' => 1,
                'message' => "Callback recibido para {$pedidoID}, estado PagoFácil {$estado}; no se marca como pagado.",
            ]);
        }

        if (preg_match('/P(\d+)-C(\d+)/', $pedidoID, $m)) {
            return $this->registrarPagoCuota((int) $m[1], (int) $m[2], $transaccion, $pedidoID);
        }

        if (preg_match('/P(\d+)/', $pedidoID, $m)) {
            return $this->registrarPagoPedido((int) $m[1], $transaccion, $pedidoID);
        }

        return response()->json([
            'error' => 1,
            'status' => 0,
            'message' => "Formato de PedidoID no reconocido: {$pedidoID}",
        ], 422);
    }

    private function registrarPagoPedido(int $pedidoId, ?string $transaccion, string $pedidoID)
    {
        $pedido = Pedido::find($pedidoId);

        if (!$pedido) {
            return response()->json([
                'error' => 1,
                'status' => 0,
                'message' => "No se encontró el pedido {$pedidoId}.",
            ], 404);
        }

        DB::transaction(function () use ($pedido, $transaccion, $pedidoID) {
            Pago::firstOrCreate(
                [
                    'id_pedido' => $pedido->id,
                    'tipo_pago' => 'qr',
                    'observacion' => 'PagoFácil ' . $pedidoID,
                ],
                [
                    'fecha_pago' => now()->toDateString(),
                    'monto' => $pedido->total,
                    'estado_pago' => 'pagado',
                    'state' => 'a',
                ]
            );

            $pedido->update([
                'estado_pedido' => 'pagado',
                'pagofacil_transaction_id' => $transaccion ?: $pedido->pagofacil_transaction_id,
            ]);
        });

        return response()->json([
            'error' => 0,
            'status' => 1,
            'message' => "Pago registrado para pedido {$pedidoId}.",
        ]);
    }

    private function registrarPagoCuota(int $pedidoId, int $cuotaId, ?string $transaccion, string $pedidoID)
    {
        $cuota = Cuota::with('planPago.pedido')->find($cuotaId);

        if (!$cuota || !$cuota->planPago || (int) $cuota->planPago->id_pedido !== $pedidoId) {
            return response()->json([
                'error' => 1,
                'status' => 0,
                'message' => "No se encontró la cuota {$cuotaId} para el pedido {$pedidoId}.",
            ], 404);
        }

        DB::transaction(function () use ($cuota, $transaccion, $pedidoID) {
            $plan = $cuota->planPago;
            $pedido = $plan->pedido;

            $cuota->update([
                'estado_cuota' => 'pagado',
                'fecha_pago' => now()->toDateString(),
                'pagofacil_transaction_id' => $transaccion ?: $cuota->pagofacil_transaction_id,
            ]);

            Pago::firstOrCreate(
                [
                    'id_pedido' => $pedido->id,
                    'tipo_pago' => 'qr',
                    'observacion' => 'PagoFácil ' . $pedidoID,
                ],
                [
                    'fecha_pago' => now()->toDateString(),
                    'monto' => $cuota->monto,
                    'estado_pago' => 'pagado',
                    'state' => 'a',
                ]
            );

            $saldo = max(0, $plan->total_deuda - Pago::where('id_pedido', $pedido->id)
                ->where('state', 'a')
                ->where('estado_pago', 'pagado')
                ->sum('monto'));

            $cuotasPendientes = $plan->cuotas()
                ->where('state', 'a')
                ->where('estado_cuota', '!=', 'pagado')
                ->count();

            $plan->update([
                'saldo_pendiente' => $saldo,
                'estado_plan' => $cuotasPendientes === 0 ? 'finalizado' : 'en curso',
            ]);

            $pedido->update([
                'estado_pedido' => $cuotasPendientes === 0 ? 'pagado' : 'pago parcial',
            ]);
        });

        return response()->json([
            'error' => 0,
            'status' => 1,
            'message' => "Pago registrado para cuota {$cuotaId} del pedido {$pedidoId}.",
        ]);
    }
}
