<?php

namespace Database\Seeders;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Producto::all() as $producto) {
            Inventario::updateOrCreate(
                ['id_producto' => $producto->id, 'tipo_movimiento' => 'entrada', 'descripcion' => 'Stock inicial'],
                ['cantidad' => 50, 'fecha_movimiento' => now()->subDays(5)->toDateString(), 'stock_actual' => 50, 'state' => 'a']
            );
        }
    }
}
