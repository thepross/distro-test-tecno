<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Promocion;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Inertia\Inertia;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(12);

        $user = Auth::user();
        $rol = $user->rol->nombre;

        $query = Venta::with(['cliente', 'usuario', 'detalles.producto', 'detalles.servicio', 'planes.cuotas', 'pagos'])
            ->where('state', 'a')
            ->orderBy('id', 'asc');

        if ($rol === 'Cliente') {
            if ($user->cliente) {
                $query->where('id_cliente', $user->cliente->id);
            } else {
                $query->where('id', -1);
            }
        } elseif ($rol === 'Secretaria') {
            $query->where('id_usuario', $user->id);
        }

        $ventas = $query->get();

        $clientes = Cliente::where('state', 'a')->get();
        $productos = Producto::where('state', 'a')->get();
        $servicios = Servicio::where('state', 'a')->get();

        return Inertia::render('Venta/Index', [
            'ventas' => $ventas,
            'clientes' => $clientes,
            'productos' => $productos,
            'servicios' => $servicios,
            'num' => $num,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $usuario = auth()->user();
    //     $cajero = auth()->user()->empleado->id;

    //     $rol = $usuario->rol->nombre;

    //     //$empleado = auth()->user()->empleado->id;
    //     //dd($cajero);

    //     // Decodificar la cadena JSON de detalles
    //     $detalles = json_decode($request->detalles, true);

    //     // Validar los datos del formulario
    //     $validatedData = $request->validate([
    //         'cliente_id' => 'required|exists:clientes,id',
    //         'servicio_id' => 'required|exists:servicios,id',
    //         'detalles' => 'required', // validar que 'detalles' esté presente
    //     ]);

    //     // Validar cada detalle individualmente
    //     foreach ($detalles as $detalle) {
    //         $detalle = (object)$detalle; // Convertir a objeto para una mejor manipulación
    //         $request->validate([
    //             'detalles.*.producto_id' => 'required|exists:productos,id',
    //             'detalles.*.cantidad' => 'required|integer|min:1',
    //         ]);
    //     }

    //     // Crear la venta
    //     if($usuario->rol->nombre === 'Cliente'){
    //         $fechaActual = Carbon::now();
    //         $venta = Venta::create([
    //             'id_cliente' => $request->cliente_id,
    //             'id_servicio' => $request->servicio_id,
    //             'fecha' => $fechaActual,
    //             'estado' => 'a',
    //             'id_promocion' => $request->has('promocion_id') ? $request->promocion_id : null,
    //             'id_empleado' => null,
    //             'total' => 0, // Inicializamos el total en 0
    //         ]);
    //     }

    //     if($usuario->rol->nombre === 'Cajero'){
    //         $fechaActual = Carbon::now();
    //         $venta = Venta::create([
    //             'id_cliente' => $request->cliente_id,
    //             'id_servicio' => $request->servicio_id,
    //             'fecha' => $fechaActual,
    //             'estado' => 'a',
    //             'id_promocion' => $request->has('promocion_id') ? $request->promocion_id : null,
    //             'id_empleado' => $cajero, // O puedes obtener el ID del empleado autenticado
    //             'total' => 0, // Inicializamos el total en 0
    //         ]);
    //     }
    //     if($usuario->rol->nombre === 'Administrador'){
    //         $fechaActual = Carbon::now();
    //         $venta = Venta::create([
    //             'id_cliente' => $request->cliente_id,
    //             'id_servicio' => $request->servicio_id,
    //             'fecha' => $fechaActual,
    //             'estado' => 'a',
    //             'id_promocion' => $request->has('promocion_id') ? $request->promocion_id : null,
    //             'id_empleado' => 1, // O puedes obtener el ID del empleado autenticado
    //             'total' => 0, // Inicializamos el total en 0
    //         ]);
    //     }

    //     // Validar que haya detalles
    //     if (empty($detalles)) {
    //         return redirect()->back()->withErrors(['detalles' => 'Se requiere agregar detalles para completar la venta.']);
    //     }

    //     // Procesar los detalles de la venta
    //     $total = 0;
    //     foreach ($detalles as $detalle) {
    //         $producto = Producto::find($detalle['producto_id']);
    //         $subtotal = $producto->precio * $detalle['cantidad'];
    //         $total += $subtotal;

    //         DetalleVenta::create([
    //             'id_venta' => $venta->id,
    //             'id_producto' => $detalle['producto_id'],
    //             'cantidad' => $detalle['cantidad'],
    //             'estado' => 'a',
    //         ]);
    //     }

    //     if ($venta->id_promocion) {
    //         $promocion = Promocion::find($venta->id_promocion);
    //         $descuento = $promocion->descuento;
    //         $totalConDescuento = $total - ($total * ($descuento / 100));
    //     } else {
    //         $totalConDescuento = $total;
    //     }

    //     // Actualizar el total de la venta
    //     $venta->update(['total' => $totalConDescuento]);

    //     return redirect()->route('venta.index');
    // }

    public function store(Request $request)
    {
        $usuario = auth()->user();
        $rol = $usuario->rol->nombre;

        // Validación básica
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'detalles' => 'required',
        ]);


        $detalles = $request->input('detalles');

        if (!is_array($detalles) || count($detalles) < 1) {
            return back()->withErrors(['detalles' => 'Debe agregar al menos un detalle.']);
        }

        foreach ($detalles as $detalle) {
            // dd($detalle);
            if (!isset($detalle['cantidad']) || $detalle['cantidad'] < 1) {
                return back()->withErrors(['detalles' => 'La cantidad es obligatoria y debe ser mínimo 1.']);
            }

            if (empty($detalle['producto_id']) && empty($detalle['servicio_id'])) {
                return back()->withErrors(['detalles' => 'Cada detalle debe tener un producto o un servicio.']);
            }

            if (!empty($detalle['producto_id']) && !Producto::find($detalle['producto_id'])) {
                return back()->withErrors(['detalles' => 'El producto no existe.']);
            }

            if (!empty($detalle['servicio_id']) && !Servicio::find($detalle['servicio_id'])) {
                return back()->withErrors(['detalles' => 'El servicio no existe.']);
            }
        }

        // Crear venta
        $fechaActual = Carbon::now();
        $venta = Venta::create([
            'id_cliente' => $request->cliente_id,
            'id_usuario' => $usuario->id,
            'fecha_venta' => $fechaActual,
            'estado' => 'pendiente',
            'total' => 0,
            'state' => 'a',
        ]);

        $total = 0;

        // Procesar detalles
        foreach ($detalles as $detalle) {
            $detalle = (object) $detalle;

            if (isset($detalle->producto_id)) {
                $item = Producto::find($detalle->producto_id);
                $precioUnitario = $item->precioConDescuento();
                $subtotal = $precioUnitario * $detalle->cantidad;

                DetalleVenta::create([
                    'id_venta' => $venta->id,
                    'id_producto' => $item->id,
                    'id_servicio' => null,
                    'cantidad' => $detalle->cantidad,
                    'precio' => $precioUnitario,
                    'state' => 'a',
                ]);
            } elseif (isset($detalle->servicio_id)) {
                $item = Servicio::find($detalle->servicio_id);
                $precioUnitario = $item->precioConDescuento();
                $subtotal = $precioUnitario * $detalle->cantidad;

                DetalleVenta::create([
                    'id_venta' => $venta->id,
                    'id_producto' => null,
                    'id_servicio' => $item->id,
                    'cantidad' => $detalle->cantidad,
                    'precio' => $precioUnitario,
                    'state' => 'a',
                ]);
            } else {
                continue; // saltar si no hay producto ni servicio
            }

            $total += $subtotal;
        }

        // Actualizar total
        $venta->update(['total' => $total]);

        return to_route('venta.index')->with('success', 'Venta creada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        // Cambiar el estado de la venta a 'inactivo'
        $venta->update(['state' => 'i']);

        // Cambiar el estado de todos los detalles de la venta a 'inactivo'
        DetalleVenta::where('id_venta', $id)->update(['state' => 'i']);

        // Redirigir a la lista de ventas con un mensaje de éxito
        return to_route('venta.index')->with('success', 'Venta eliminada correctamente.');
    }

    public function detalles(Venta $venta)
    {
        $html = '<div class="container">';
        $html .= '<h2>Venta #' . $venta->id . '</h2>';
        $html .= '<p> <strong> Cliente: </strong> ' . ($venta->cliente->nombre ?? 'Sin cliente') . '</p>';
        $html .= '<p> <strong> Fecha: </strong> ' . $venta->fecha_venta . '</p>';
        $html .= '<p> <strong> Estado: </strong> ' . $venta->estado . '</p>';

        $productos = $venta->detalles->filter(fn($d) => $d->id_producto !== null);
        $servicios = $venta->detalles->filter(fn($d) => $d->id_servicio !== null);

        $totalSinDescuento = 0;

        $html .= '<h3>Detalle de Venta:</h3>';

        // Tabla de Productos
        if ($productos->count() > 0) {
            $html .= '<table class="table">';
            $html .= '<thead><tr><th>Producto</th><th>P/U</th><th>Cantidad</th><th>Subtotal</th></tr></thead>';
            $html .= '<tbody>';

            foreach ($productos as $detalle) {
                $precioUnitario = $detalle->producto->precioConDescuento();
                $subtotal = $detalle->cantidad * $precioUnitario;
                $totalSinDescuento += $subtotal;

                $html .= '<tr>';
                $html .= '<td>' . $detalle->producto->nombre . '</td>';
                $html .= '<td>' . number_format($precioUnitario, 2) . '</td>';
                $html .= '<td>' . $detalle->cantidad . '</td>';
                $html .= '<td>' . number_format($subtotal, 2) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        }

        // Tabla de Servicios
        if ($servicios->count() > 0) {
            $html .= '<table class="table">';
            $html .= '<thead><tr><th>Servicio</th><th>P/U</th><th>Cantidad</th><th>Subtotal</th></tr></thead>';
            $html .= '<tbody>';

            foreach ($servicios as $detalle) {
                $precioUnitario = $detalle->servicio->precioConDescuento();
                $subtotal = $detalle->cantidad * $precioUnitario;
                $totalSinDescuento += $subtotal;

                $html .= '<tr>';
                $html .= '<td>' . $detalle->servicio->descripcion . '</td>';
                $html .= '<td>' . number_format($precioUnitario, 2) . '</td>';
                $html .= '<td>' . $detalle->cantidad . '</td>';
                $html .= '<td>' . number_format($subtotal, 2) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        }

        $html .= '<h4 class="text-right">Total: ' . number_format($totalSinDescuento, 2) . '</h4>';


        //----------------------- DETALLE PAGOS -----------------------------
        $html .= '<h3>Pagos Realizados</h3>';

        if ($venta->pagos->count() == 0) {
            $html .= '<p>No hay pagos registrados.</p>';
        } else {
            $html .= '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead><tbody>';

            foreach ($venta->pagos as $pago) {
                $html .= '<tr>
                    <td>' . $pago->fecha_pago . '</td>
                    <td>$' . number_format($pago->monto, 2) . '</td>
                    <td>' . ucfirst($pago->tipo_pago) . '</td>
                    <td>' . $pago->estado_pago . '</td>
                </tr>';
            }

            $html .= '</tbody></table>';
        }

        $html .= '<h3>Cuotas del Plan de Pago</h3>';

        if ($venta->planes->count() == 0) {
            $html .= '<p>No hay planes de pago para esta venta.</p>';
        } else {
            foreach ($venta->planes as $plan) {
                $html .= '<h5>Plan #' . $plan->id . ' - Total deuda: $' . number_format($plan->total_deuda, 2) . '</h5>';
                $html .= '<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th># Cuota</th>
                                    <th>Monto</th>
                                    <th>Fecha vencimiento</th>
                                    <th>Saldo pendiente</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>';

                foreach ($plan->cuotas as $index => $cuota) {
                    $html .= '<tr>
                        <td>' . ($index + 1) . '</td>
                        <td>$' . number_format($cuota->monto, 2) . '</td>
                        <td>' . $cuota->fecha_vencimiento . '</td>
                        <td>$' . number_format($cuota->monto, 2) . '</td> <!-- Saldo = monto completo -->
                        <td>' . ucfirst($cuota->estado) . '</td>
                    </tr>';
                }

                $html .= '</tbody></table>';
            }
        }

        $html .= '</div>';

        return $html;
    }
}
