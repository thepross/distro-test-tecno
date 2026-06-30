<?php

namespace Database\Seeders;

use App\Models\Contador;
use Illuminate\Database\Seeder;

class ContadorSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            1 => 'Roles', 2 => 'Usuarios', 3 => 'Productos', 4 => 'Compras', 5 => 'Detalle de compras',
            6 => 'Inventario', 7 => 'Pedidos', 8 => 'Detalle de pedidos', 9 => 'Entregas',
            10 => 'Pagos', 11 => 'Plan de pago', 12 => 'Cuotas', 13 => 'Reportes',
            14 => 'Privilegios', 15 => 'Configuración'
        ];

        foreach ($modulos as $id => $nombre) {
            Contador::updateOrCreate(['id' => $id], ['nombre' => $nombre, 'visitas' => 0, 'tipo' => $id]);
        }
    }
}
