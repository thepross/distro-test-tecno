<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PrivilegioSeeder::class,
            ProductoSeeder::class,
            CompraSeeder::class,
            DetalleCompraSeeder::class,
            InventarioSeeder::class,
            PedidoSeeder::class,
            DetallePedidoSeeder::class,
            EntregaSeeder::class,
            PagoSeeder::class,
            PlanPagoSeeder::class,
            CuotaSeeder::class,
            ContadorSeeder::class,
        ]);
    }
}
