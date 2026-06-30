<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Cuota;
use App\Models\Pedido;
use App\Models\PlanPago;
use App\Services\PagoFacilService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PlanPagoController extends Controller
{
    protected PagoFacilService $pagoService;

    public function __construct(PagoFacilService $pagoService)
    {
        $this->pagoService = $pagoService;
    }

    public function index()
    {
        $num = (new Contador())->contarModel(11);
        return Inertia::render('Planes/Index', [
            'planes' => PlanPago::with(['pedido', 'cuotas'])->where('state', 'a')->orderByDesc('id')->get(),
            'pedidos' => Pedido::where('state', 'a')->orderByDesc('id')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id',
            'cantidad_cuotas' => 'required|integer|min:1',
            'monto_cuota' => 'required|numeric|min:0.01',
            'total_deuda' => 'required|numeric|min:0.01',
            'saldo_pendiente' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'estado_plan' => 'required|string|max:100',
        ]);

        $data['saldo_pendiente'] = $data['saldo_pendiente'] ?? $data['total_deuda'];
        $data['state'] = 'a';
        $plan = PlanPago::create($data);
        $this->generarCuotas($plan);

        return to_route('planes.index')->with('success', 'Plan de pago agregado exitosamente.');
    }

    public function guardarPlan(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'cantidad_cuotas' => 'required|integer|min:1',
            'total_deuda' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
            'estado_plan' => 'required|string|max:100',
            'cuotas' => 'required|array|min:1',
            'cuotas.*.monto' => 'required|numeric|min:0.01',
            'cuotas.*.fecha_vencimiento' => 'required|date',
        ]);

        if ($pedido->planPago()->where('state', 'a')->exists()) {
            return back()->withErrors(['plan' => 'Este pedido ya tiene un plan de pago activo.']);
        }

        DB::transaction(function () use ($pedido, $data) {
            $montoCuota = round((float) $data['total_deuda'] / (int) $data['cantidad_cuotas'], 2);

            $plan = PlanPago::create([
                'id_pedido' => $pedido->id,
                'cantidad_cuotas' => $data['cantidad_cuotas'],
                'monto_cuota' => $montoCuota,
                'total_deuda' => $data['total_deuda'],
                'saldo_pendiente' => $data['total_deuda'],
                'fecha_inicio' => $data['fecha_inicio'],
                'estado_plan' => $data['estado_plan'],
                'state' => 'a',
            ]);

            foreach ($data['cuotas'] as $index => $cuota) {
                Cuota::create([
                    'id_plan_pago' => $plan->id,
                    'numero_cuota' => $index + 1,
                    'fecha_vencimiento' => $cuota['fecha_vencimiento'],
                    'monto' => $cuota['monto'],
                    'estado_cuota' => 'pendiente',
                    'fecha_pago' => null,
                    'state' => 'a',
                ]);
            }

            $pedido->update(['estado_pedido' => 'plan de pago']);
        });

        return to_route('pedido.index')->with('success', 'Plan de pago creado y cuotas generadas.');
    }

    public function pagarCuota(Request $request, Pedido $pedido)
    {
        $request->validate([
            'cuotas' => 'required|array|min:1',
            'cuotas.*' => 'exists:cuotas,id',
        ], [
            'cuotas.required' => 'Debe seleccionar al menos una cuota pendiente.',
        ]);

        $cuota = Cuota::whereIn('id', $request->cuotas)
            ->where('estado_cuota', 'pendiente')
            ->whereHas('planPago', fn ($q) => $q->where('id_pedido', $pedido->id))
            ->orderBy('numero_cuota')
            ->first();

        if (!$cuota) {
            return back()->withErrors(['cuotas' => 'No se encontró una cuota pendiente válida para este pedido.']);
        }

        return to_route('planes.cuota.pagarQR', ['cuota' => $cuota->id]);
    }

    public function pagarQR(Pedido $pedido)
    {
        $resultado = ['qrBase64' => null, 'transactionId' => null];
        $pedido->load('cliente', 'detalles.producto', 'pagos', 'planPago.cuotas');

        try {
            $resultado = $this->pagoService->generarQrPedido($pedido);
            $pedido->update([
                'pagofacil_transaction_id' => $resultado['transactionId'] ?? null,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al conectar con PagoFácil: ' . $e->getMessage()]);
        }

        return Inertia::render('Pedido/ShowQrPedido', [
            'pedido' => $pedido->fresh(['cliente', 'detalles.producto', 'pagos', 'planPago.cuotas']),
            'qrImage' => $resultado['qrBase64'] ?? null,
            'callbackUrl' => config('services.pagofacil.callback_url') ?: route('pagofacil.callback'),
            'montoPrueba' => $this->pagoService->montoPrueba(),
        ]);
    }

    public function pagarCuota2(Cuota $cuota)
    {
        $resultado = ['qrBase64' => null, 'transactionId' => null];
        $cuota->load('planPago.pedido.cliente');

        if ($cuota->estado_cuota !== 'pagado') {
            try {
                $resultado = $this->pagoService->generarQrParaCuota($cuota);
                $cuota->update([
                    'pagofacil_transaction_id' => $resultado['transactionId'] ?? null,
                ]);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Error al conectar con PagoFácil: ' . $e->getMessage()]);
            }
        }

        return Inertia::render('Pedido/ShowQrCuota', [
            'cuota' => $cuota->fresh('planPago.pedido.cliente'),
            'qrImage' => $resultado['qrBase64'] ?? null,
            'callbackUrl' => config('services.pagofacil.callback_url') ?: route('pagofacil.callback'),
            'montoPrueba' => $this->pagoService->montoPrueba(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id',
            'cantidad_cuotas' => 'required|integer|min:1',
            'monto_cuota' => 'required|numeric|min:0.01',
            'total_deuda' => 'required|numeric|min:0.01',
            'saldo_pendiente' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'estado_plan' => 'required|string|max:100',
        ]);

        PlanPago::findOrFail($id)->update($data);
        return to_route('planes.index')->with('success', 'Plan de pago actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $plan = PlanPago::findOrFail($id);
        $plan->cuotas()->update(['state' => 'i']);
        $plan->update(['state' => 'i']);

        return to_route('planes.index')->with('success', 'Plan de pago eliminado exitosamente.');
    }

    private function generarCuotas(PlanPago $plan): void
    {
        for ($i = 1; $i <= $plan->cantidad_cuotas; $i++) {
            Cuota::create([
                'id_plan_pago' => $plan->id,
                'numero_cuota' => $i,
                'fecha_vencimiento' => Carbon::parse($plan->fecha_inicio)->addMonths($i - 1)->toDateString(),
                'monto' => $plan->monto_cuota,
                'estado_cuota' => 'pendiente',
                'fecha_pago' => null,
                'state' => 'a',
            ]);
        }
    }
}
