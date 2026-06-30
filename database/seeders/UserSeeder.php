<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('nombre', 'Administrador')->first();
        $vendedor = Role::where('nombre', 'Vendedor')->first();
        $almacenero = Role::where('nombre', 'Almacenero')->first();
        $cliente = Role::where('nombre', 'Cliente')->first();
        $proveedor = Role::where('nombre', 'Proveedor')->first();
        $repartidor = Role::where('nombre', 'Repartidor')->first();

        $usuarios = [
            ['nombre' => 'Admin', 'apellido' => 'Principal', 'ci' => '1000001', 'telefono' => '70000001', 'email' => 'admin@carla.com', 'username' => 'admin', 'password' => 'secret', 'id_rol' => $admin->id, 'direccion' => 'Oficina central'],
            ['nombre' => 'Valeria', 'apellido' => 'Rojas', 'ci' => '1000002', 'telefono' => '70000002', 'email' => 'ventas@carla.com', 'username' => 'vendedora', 'password' => 'secret', 'id_rol' => $vendedor->id, 'direccion' => 'Sucursal norte'],
            ['nombre' => 'Carlos', 'apellido' => 'Mamani', 'ci' => '1000003', 'telefono' => '70000003', 'email' => 'almacen@carla.com', 'username' => 'almacen', 'password' => 'secret', 'id_rol' => $almacenero->id, 'direccion' => 'Depósito'],
            ['nombre' => 'Luis', 'apellido' => 'Quispe', 'ci' => '1000004', 'telefono' => '70000004', 'email' => 'reparto@carla.com', 'username' => 'reparto', 'password' => 'secret', 'id_rol' => $repartidor->id, 'direccion' => 'Zona central'],
            ['nombre' => 'Tienda', 'apellido' => 'El Ahorro', 'ci' => 'NIT-001', 'telefono' => '70000005', 'email' => 'cliente@carla.com', 'username' => 'cliente', 'password' => 'secret', 'id_rol' => $cliente->id, 'direccion' => 'Mercado principal'],
            ['nombre' => 'Proveedor', 'apellido' => 'Mayorista SRL', 'ci' => 'NIT-002', 'telefono' => '70000006', 'email' => 'proveedor@carla.com', 'username' => 'proveedor', 'password' => 'secret', 'id_rol' => $proveedor->id, 'direccion' => 'Parque industrial'],
        ];

        foreach ($usuarios as $usuario) {
            $plainPassword = $usuario['password'];
            $usuario['password'] = Hash::make($plainPassword);
            $usuario['state'] = 'a';
            Usuario::updateOrCreate(['email' => $usuario['email']], $usuario);
        }
    }
}
