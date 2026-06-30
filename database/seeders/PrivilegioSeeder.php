<?php

namespace Database\Seeders;

use App\Models\Privilegio;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PrivilegioSeeder extends Seeder
{
    public function run(): void
    {
        $funcionalidades = [
            'Administracion', 'Rol', 'Usuario', 'Privilegio',
            'Producto', 'Compra', 'DetalleCompra', 'Inventario',
            'Pedido', 'DetallePedido', 'Entrega', 'Pago', 'PlanPago', 'Cuota', 'Reportes'
        ];

        $perfiles = [
            'Administrador' => array_fill_keys($funcionalidades, ['leer' => true, 'agregar' => true, 'modificar' => true, 'borrar' => true]),
            'Vendedor' => [
                'Producto' => ['leer' => true], 'Pedido' => ['leer' => true, 'agregar' => true, 'modificar' => true],
                'DetallePedido' => ['leer' => true, 'agregar' => true, 'modificar' => true], 'Pago' => ['leer' => true, 'agregar' => true],
                'PlanPago' => ['leer' => true, 'agregar' => true], 'Cuota' => ['leer' => true, 'modificar' => true], 'Reportes' => ['leer' => true]
            ],
            'Almacenero' => [
                'Producto' => ['leer' => true, 'agregar' => true, 'modificar' => true],
                'Compra' => ['leer' => true, 'agregar' => true, 'modificar' => true],
                'DetalleCompra' => ['leer' => true, 'agregar' => true, 'modificar' => true],
                'Inventario' => ['leer' => true, 'agregar' => true, 'modificar' => true], 'Reportes' => ['leer' => true]
            ],
            'Repartidor' => [
                'Pedido' => ['leer' => true], 'Entrega' => ['leer' => true, 'modificar' => true]
            ],
            'Cliente' => [
                'Producto' => ['leer' => true], 'Pedido' => ['leer' => true, 'agregar' => true], 'Pago' => ['leer' => true]
            ],
            'Proveedor' => [
                'Compra' => ['leer' => true]
            ],
        ];

        foreach ($perfiles as $nombreRol => $permisosRol) {
            $rol = Role::where('nombre', $nombreRol)->first();
            if (!$rol) continue;

            foreach ($funcionalidades as $funcionalidad) {
                $ops = $permisosRol[$funcionalidad] ?? [];
                Privilegio::updateOrCreate(
                    ['id_rol' => $rol->id, 'funcionalidad' => $funcionalidad],
                    [
                        'leer' => (bool)($ops['leer'] ?? false),
                        'agregar' => (bool)($ops['agregar'] ?? false),
                        'modificar' => (bool)($ops['modificar'] ?? false),
                        'borrar' => (bool)($ops['borrar'] ?? false),
                        'state' => 'a',
                    ]
                );
            }
        }
    }
}
