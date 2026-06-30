<?php

namespace Database\Seeders;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DetalleCompraSeeder extends Seeder
{
    public function run(): void
    {
        $compra = Compra::first();
        foreach (Producto::all() as $producto) {
            $cantidad = 50;
            $subtotal = $cantidad * $producto->precio_compra;
            DetalleCompra::updateOrCreate(
                ['id_compra' => $compra->id, 'id_producto' => $producto->id],
                ['cantidad' => $cantidad, 'precio_compra' => $producto->precio_compra, 'subtotal' => $subtotal, 'state' => 'a']
            );
        }
        $compra->total = DetalleCompra::where('id_compra', $compra->id)->sum('subtotal');
        $compra->save();
    }
}
