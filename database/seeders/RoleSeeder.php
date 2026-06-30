<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso completo al sistema'],
            ['nombre' => 'Vendedor', 'descripcion' => 'Registra pedidos y pagos'],
            ['nombre' => 'Almacenero', 'descripcion' => 'Gestiona compras, productos e inventario'],
            ['nombre' => 'Repartidor', 'descripcion' => 'Gestiona entregas de pedidos'],
            ['nombre' => 'Cliente', 'descripcion' => 'Cliente de la distribuidora'],
            ['nombre' => 'Proveedor', 'descripcion' => 'Proveedor de productos'],
        ];

        foreach ($roles as $rol) {
            Role::updateOrCreate(['nombre' => $rol['nombre']], $rol + ['state' => 'a']);
        }
    }
}
