<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoFacilWebHookController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PlanPagoController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Landing'));

Route::get('/dashboard', [ReporteController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/rol', RoleController::class)->names('rol')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/usuario', UsuarioController::class)->names('usuario')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/privilegio', PrivilegioController::class)->names('privilegio')->only(['index', 'store', 'update', 'destroy']);
    Route::post('/privilegios/asignar/{rol}', [PrivilegioController::class, 'asignar'])->name('privilegios.asignar');
    Route::put('/privilegios/{rol}', [PrivilegioController::class, 'update'])->name('privilegio.update');

    Route::resource('/empresa', EmpresaController::class)->names('empresa')->only(['index']);
    Route::put('/empresa/{empresa}/nombre', [EmpresaController::class, 'nombre'])->name('empresa.nombre');
    Route::put('/empresa/{empresa}/direccion', [EmpresaController::class, 'direccion'])->name('empresa.direccion');
    Route::put('/empresa/{empresa}/correo', [EmpresaController::class, 'correo'])->name('empresa.correo');
    Route::put('/empresa/{empresa}/telefono', [EmpresaController::class, 'telefono'])->name('empresa.telefono');

    Route::resource('/producto', ProductoController::class)->names('producto')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/compra', CompraController::class)->names('compra')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/detalle_compra', DetalleCompraController::class)->names('detalle_compra')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/inventario', InventarioController::class)->names('inventario')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/pedido', PedidoController::class)->names('pedido')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/detalle_pedido', DetallePedidoController::class)->names('detalle_pedido')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/entrega', EntregaController::class)->names('entrega')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/pago', PagoController::class)->names('pago')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/planes', PlanPagoController::class)->names('planes')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/cuota', CuotaController::class)->names('cuota')->only(['index', 'store', 'update', 'destroy']);
    Route::post('/pedidos/{pedido}/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::post('/pedidos/{pedido}/plan', [PlanPagoController::class, 'guardarPlan'])->name('planes.pedido.store');
    Route::post('/pedidos/{pedido}/pagar-cuotas', [PlanPagoController::class, 'pagarCuota'])->name('planes.pedido.pagarCuota');
    Route::get('/pedidos/{pedido}/pagar-qr', [PlanPagoController::class, 'pagarQR'])->name('planes.pedido.pagarQR');
    Route::get('/cuotas/{cuota}/pagar-qr', [PlanPagoController::class, 'pagarCuota2'])->name('planes.cuota.pagarQR');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes-buscar', [ReporteController::class, 'buscador'])->name('reportes.buscar');
    Route::get('/estadisticas', [ReporteController::class, 'estadisticas'])->name('estadisticas.index');
    Route::get('/cargar-estilo/{estilo}', [ReporteController::class, 'cargarEstilo'])->name('cargarEstilo');
});

Route::get('/unauthorized', [EmpresaController::class, 'intruso'])->name('intruso');

Route::post('/pagofacil/callback', [PagoFacilWebHookController::class, 'callback'])->name('pagofacil.callback');

require __DIR__ . '/auth.php';
