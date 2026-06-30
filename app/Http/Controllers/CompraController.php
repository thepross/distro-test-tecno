<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Contador;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompraController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(4);
        $proveedorRole = Role::where('nombre', 'Proveedor')->first();
        $proveedores = Usuario::where('state', 'a')
            ->when($proveedorRole, fn($q) => $q->where('id_rol', $proveedorRole->id))
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Compra/Index', [
            'compras' => Compra::with(['proveedor', 'detalles.producto'])
                ->where('state', 'a')
                ->orderByDesc('id')
                ->get(),
            'proveedores' => $proveedores,
            'productos' => Producto::where('state', 'a')->orderBy('nombre')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validarCompra($request);

        DB::transaction(function () use ($data) {
            $compra = Compra::create([
                'fecha_compra' => $data['fecha_compra'],
                'id_proveedor' => $data['id_proveedor'] ?? null,
                'total' => 0,
                'estado_compra' => $data['estado_compra'],
                'observacion' => $data['observacion'] ?? null,
                'state' => 'a',
            ]);

            $total = $this->guardarDetalles($compra, $data['detalles']);
            $compra->update(['total' => $total]);
        });

        return to_route('compra.index')->with('success', 'Compra agregada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $this->validarCompra($request);

        DB::transaction(function () use ($data, $id) {
            $compra = Compra::findOrFail($id);
            $compra->update([
                'fecha_compra' => $data['fecha_compra'],
                'id_proveedor' => $data['id_proveedor'] ?? null,
                'estado_compra' => $data['estado_compra'],
                'observacion' => $data['observacion'] ?? null,
            ]);

            DetalleCompra::where('id_compra', $compra->id)->update(['state' => 'i']);

            $total = $this->guardarDetalles($compra, $data['detalles']);
            $compra->update(['total' => $total]);
        });

        return to_route('compra.index')->with('success', 'Compra actualizada exitosamente.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            Compra::findOrFail($id)->update(['state' => 'i']);
            DetalleCompra::where('id_compra', $id)->update(['state' => 'i']);
        });

        return to_route('compra.index')->with('success', 'Compra eliminada exitosamente.');
    }

    private function validarCompra(Request $request): array
    {
        return $request->validate([
            'fecha_compra' => 'required|date',
            'id_proveedor' => 'nullable|exists:users,id',
            'estado_compra' => 'required|string|max:100',
            'observacion' => 'nullable|string|max:255',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0',
        ], [
            'detalles.required' => 'Debe agregar al menos un producto a la compra.',
            'detalles.min' => 'Debe agregar al menos un producto a la compra.',
        ]);
    }

    private function guardarDetalles(Compra $compra, array $detalles): float
    {
        $total = 0;

        foreach ($detalles as $detalle) {
            $cantidad = (int) $detalle['cantidad'];
            $precioCompra = (float) $detalle['precio_compra'];
            $subtotal = $cantidad * $precioCompra;
            $total += $subtotal;

            DetalleCompra::create([
                'id_compra' => $compra->id,
                'id_producto' => $detalle['id_producto'],
                'cantidad' => $cantidad,
                'precio_compra' => $precioCompra,
                'subtotal' => $subtotal,
                'state' => 'a',
            ]);
        }

        return $total;
    }
}
