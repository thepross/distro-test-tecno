<?php

namespace App\Http\Controllers;

use App\Models\BitacoraAcceso;
use App\Models\Compra;
use App\Models\Contador;
use App\Models\Cuota;
use App\Models\Inventario;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ReporteController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(13);
        $cantidadPedidos = Pedido::where('state', 'a')->count();
        $cantidadCompras = Compra::where('state', 'a')->count();
        $cantidadProductos = Producto::where('state', 'a')->count();
        $cantidadUsuarios = Usuario::where('state', 'a')->count();
        $totalVendido = Pedido::where('state', 'a')->sum('total');
        $totalComprado = Compra::where('state', 'a')->sum('total');
        $totalPagado = Pago::where('state', 'a')->sum('monto');
        $cuotasPendientes = Cuota::where('state', 'a')->where('estado_cuota', 'pendiente')->count();
        $cantidadVisitas = Contador::sum('visitas');

        $pedidosAgrupados = Pedido::where('state', 'a')
            ->get()
            ->groupBy(fn($pedido) => \Carbon\Carbon::parse($pedido->fecha_pedido)->format('m'))
            ->sortKeys();

        $mes = [];
        $cantidad = [];
        foreach ($pedidosAgrupados as $mesNumero => $pedidos) {
            $mes[] = $mesNumero;
            $cantidad[] = $pedidos->count();
        }

        $productosTop = DB::table('productos as p')
            ->join('detalle_pedido as dp', 'p.id', '=', 'dp.id_producto')
            ->join('pedidos as pe', 'dp.id_pedido', '=', 'pe.id')
            ->where('p.state', 'a')
            ->where('dp.state', 'a')
            ->where('pe.state', 'a')
            ->select('p.nombre as producto', DB::raw('SUM(dp.cantidad) as total_vendido'))
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $stockBajo = Producto::where('state', 'a')
            ->orderBy('nombre')
            ->get()
            ->map(function ($producto) {
                $stockActual = Inventario::where('id_producto', $producto->id)
                    ->where('state', 'a')
                    ->orderByDesc('id')
                    ->value('stock_actual') ?? 0;

                return (object) [
                    'nombre' => $producto->nombre,
                    'stock_minimo' => $producto->stock_minimo,
                    'stock_actual' => $stockActual,
                ];
            })
            ->filter(fn($item) => $item->stock_actual <= $item->stock_minimo)
            ->values();

        $loginAceptados = Schema::hasTable('bitacora_accesos')
            ? BitacoraAcceso::where('evento', 'login_aceptado')->count()
            : 0;

        $loginFallados = Schema::hasTable('bitacora_accesos')
            ? BitacoraAcceso::where('evento', 'login_fallado')->count()
            : 0;

        $ultimosAccesos = Schema::hasTable('bitacora_accesos')
            ? BitacoraAcceso::with('usuario')
                ->where('state', 'a')
                ->latest()
                ->limit(10)
                ->get()
                ->map(fn($item) => [
                    'fecha' => optional($item->created_at)->format('d/m/Y H:i'),
                    'usuario' => optional($item->usuario)->name ?: ($item->email ?: 'No identificado'),
                    'email' => $item->email,
                    'evento' => $item->evento,
                    'estado' => $item->estado,
                    'ip' => $item->ip,
                    'descripcion' => $item->descripcion,
                ])
            : collect();

        $recursosMasAccedidos = Contador::orderByDesc('visitas')
            ->limit(10)
            ->get(['nombre', 'visitas']);

        return Inertia::render('Reportes/Index', compact(
            'cantidadPedidos', 'cantidadCompras', 'cantidadProductos', 'cantidadUsuarios',
            'totalVendido', 'totalComprado', 'totalPagado', 'cuotasPendientes', 'cantidadVisitas',
            'mes', 'cantidad', 'productosTop', 'stockBajo', 'loginAceptados', 'loginFallados',
            'ultimosAccesos', 'recursosMasAccedidos', 'num'
        ));
    }

    public function buscador(Request $request)
    {
        $num = (new Contador())->contarModel(13);
        $search = trim((string) $request->input('buscar', ''));
        $searchLower = mb_strtolower($search);

        $configuracion = [
            [
                'tabla' => 'users',
                'modelo' => 'Usuarios',
                'ruta' => route('usuario.index', absolute: false),
                'titulo' => 'nombre',
                'campos' => ['nombre', 'apellido', 'ci', 'telefono', 'email', 'direccion', 'username'],
            ],
            [
                'tabla' => 'roles',
                'modelo' => 'Roles',
                'ruta' => route('rol.index', absolute: false),
                'titulo' => 'nombre',
                'campos' => ['nombre', 'descripcion'],
            ],
            [
                'tabla' => 'privilegios',
                'modelo' => 'Privilegios',
                'ruta' => route('privilegio.index', absolute: false),
                'titulo' => 'funcionalidad',
                'campos' => ['funcionalidad', 'agregar', 'borrar', 'modificar', 'leer'],
            ],
            [
                'tabla' => 'productos',
                'modelo' => 'Productos',
                'ruta' => route('producto.index', absolute: false),
                'titulo' => 'nombre',
                'campos' => ['nombre', 'descripcion', 'categoria', 'marca', 'unidad_medida', 'precio_compra', 'precio_venta', 'codigo_qr', 'stock_minimo'],
            ],
            [
                'tabla' => 'compras',
                'modelo' => 'Compras',
                'ruta' => route('compra.index', absolute: false),
                'titulo' => 'estado_compra',
                'campos' => ['fecha_compra', 'id_proveedor', 'total', 'estado_compra', 'observacion'],
            ],
            [
                'tabla' => 'detalle_compra',
                'modelo' => 'Detalle de compras',
                'ruta' => route('compra.index', absolute: false),
                'titulo' => 'id_compra',
                'campos' => ['id_compra', 'id_producto', 'cantidad', 'precio_compra', 'subtotal'],
            ],
            [
                'tabla' => 'inventario',
                'modelo' => 'Inventario',
                'ruta' => route('inventario.index', absolute: false),
                'titulo' => 'tipo_movimiento',
                'campos' => ['id_producto', 'tipo_movimiento', 'cantidad', 'fecha_movimiento', 'stock_actual', 'descripcion'],
            ],
            [
                'tabla' => 'pedidos',
                'modelo' => 'Pedidos',
                'ruta' => route('pedido.index', absolute: false),
                'titulo' => 'estado_pedido',
                'campos' => ['fecha_pedido', 'id_cliente', 'total', 'estado_pedido', 'observacion', 'pagofacil_transaction_id'],
            ],
            [
                'tabla' => 'detalle_pedido',
                'modelo' => 'Detalle de pedidos',
                'ruta' => route('pedido.index', absolute: false),
                'titulo' => 'id_pedido',
                'campos' => ['id_pedido', 'id_producto', 'cantidad', 'precio_venta', 'subtotal'],
            ],
            [
                'tabla' => 'entregas',
                'modelo' => 'Entregas',
                'ruta' => route('entrega.index', absolute: false),
                'titulo' => 'estado_entrega',
                'campos' => ['id_pedido', 'id_repartidor', 'fecha_salida', 'fecha_entrega', 'direccion_entrega', 'estado_entrega', 'observacion'],
            ],
            [
                'tabla' => 'pagos',
                'modelo' => 'Pagos',
                'ruta' => route('pago.index', absolute: false),
                'titulo' => 'tipo_pago',
                'campos' => ['id_pedido', 'fecha_pago', 'monto', 'tipo_pago', 'estado_pago', 'observacion'],
            ],
            [
                'tabla' => 'plan_pago',
                'modelo' => 'Plan de pago',
                'ruta' => route('planes.index', absolute: false),
                'titulo' => 'estado_plan',
                'campos' => ['id_pedido', 'cantidad_cuotas', 'monto_cuota', 'total_deuda', 'saldo_pendiente', 'fecha_inicio', 'estado_plan'],
            ],
            [
                'tabla' => 'cuotas',
                'modelo' => 'Cuotas',
                'ruta' => route('cuota.index', absolute: false),
                'titulo' => 'estado_cuota',
                'campos' => ['id_plan_pago', 'numero_cuota', 'fecha_vencimiento', 'monto', 'estado_cuota', 'fecha_pago', 'pagofacil_transaction_id'],
            ],
            [
                'tabla' => 'bitacora_accesos',
                'modelo' => 'Bitácora de accesos',
                'ruta' => route('reportes.index', absolute: false),
                'titulo' => 'evento',
                'campos' => ['email', 'evento', 'estado', 'ip', 'ruta', 'descripcion'],
            ],
        ];

        $data = [];

        foreach ($configuracion as $item) {
            if (! Schema::hasTable($item['tabla'])) {
                continue;
            }

            $columnas = Schema::getColumnListing($item['tabla']);
            $campos = array_values(array_intersect($item['campos'], $columnas));

            if (empty($campos)) {
                continue;
            }

            $query = DB::table($item['tabla'])->select(array_merge(['id'], $campos));

            if (in_array('state', $columnas, true)) {
                $query->where('state', 'a');
            }

            $registros = $query->orderByDesc('id')->limit(300)->get();

            foreach ($registros as $registro) {
                $valores = collect($campos)
                    ->map(fn($campo) => $campo . ': ' . ($registro->{$campo} ?? ''))
                    ->implode(' | ');

                if ($searchLower !== '' && ! str_contains(mb_strtolower($valores), $searchLower)) {
                    continue;
                }

                $titulo = $registro->{$item['titulo']} ?? ('Registro #' . $registro->id);

                $data[] = [
                    'id' => $item['tabla'] . '-' . $registro->id,
                    'ruta' => $item['ruta'],
                    'nombre' => (string) $titulo,
                    'detalle' => $valores,
                    'modelo' => $item['modelo'],
                ];
            }
        }

        return Inertia::render('Estadisticas/Index', [
            'data' => array_slice($data, 0, 100),
            'buscar' => $search,
            'num' => $num,
        ]);
    }

    public function estadisticas()
    {
        $num = (new Contador())->contarModel(13);
        $datos = Contador::select('nombre', 'visitas')->orderByDesc('visitas')->get();
        return Inertia::render('Estadisticas/Resultado', ['datos' => $datos, 'num' => $num]);
    }

    public function cargarEstilo($id)
    {
        $usuario = auth()->user();
        $usuario->estilo = $id;
        $usuario->save();
        return redirect()->back();
    }
}
