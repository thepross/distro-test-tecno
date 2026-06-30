<?php

namespace Database\Seeders;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DetallePedidoSeeder extends Seeder
{
    public function run(): void
    {
        $pedido = Pedido::first();
        foreach (Producto::limit(2)->get() as $producto) {
            $cantidad = 3;
            $subtotal = $cantidad * $producto->precio_venta;
            DetallePedido::updateOrCreate(
                ['id_pedido' => $pedido->id, 'id_producto' => $producto->id],
                ['cantidad' => $cantidad, 'precio_venta' => $producto->precio_venta, 'subtotal' => $subtotal, 'state' => 'a']
            );
        }
        $pedido->total = DetallePedido::where('id_pedido', $pedido->id)->sum('subtotal');
        $pedido->save();
    }
}
