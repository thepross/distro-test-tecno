<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['nombre' => 'Arroz Grano de Oro 1kg', 'descripcion' => 'Arroz seleccionado', 'categoria' => 'Abarrotes', 'marca' => 'Grano de Oro', 'unidad_medida' => 'Bolsa', 'precio_compra' => 5.20, 'precio_venta' => 6.50, 'codigo_qr' => 'PROD-ARROZ-001', 'stock_minimo' => 20],
            ['nombre' => 'Aceite Familiar 900ml', 'descripcion' => 'Aceite comestible', 'categoria' => 'Abarrotes', 'marca' => 'Familiar', 'unidad_medida' => 'Botella', 'precio_compra' => 9.00, 'precio_venta' => 11.50, 'codigo_qr' => 'PROD-ACEITE-002', 'stock_minimo' => 15],
            ['nombre' => 'Detergente LimpiaMax 500g', 'descripcion' => 'Detergente en polvo', 'categoria' => 'Limpieza', 'marca' => 'LimpiaMax', 'unidad_medida' => 'Bolsa', 'precio_compra' => 6.00, 'precio_venta' => 8.00, 'codigo_qr' => 'PROD-DETER-003', 'stock_minimo' => 12],
            ['nombre' => 'Galletas Dulce Hogar', 'descripcion' => 'Paquete surtido', 'categoria' => 'Snacks', 'marca' => 'Dulce Hogar', 'unidad_medida' => 'Caja', 'precio_compra' => 18.00, 'precio_venta' => 24.00, 'codigo_qr' => 'PROD-GALLETA-004', 'stock_minimo' => 8],
        ];

        foreach ($productos as $producto) {
            Producto::updateOrCreate(['codigo_qr' => $producto['codigo_qr']], $producto + ['state' => 'a']);
        }
    }
}
