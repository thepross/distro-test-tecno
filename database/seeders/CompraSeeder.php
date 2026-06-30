<?php

namespace Database\Seeders;

use App\Models\Compra;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class CompraSeeder extends Seeder
{
    public function run(): void
    {
        $proveedorRole = Role::where('nombre', 'Proveedor')->first();
        $proveedor = Usuario::where('id_rol', $proveedorRole?->id)->first();
        Compra::updateOrCreate(['observacion' => 'Compra inicial de productos'], [
            'fecha_compra' => now()->subDays(5)->toDateString(),
            'id_proveedor' => $proveedor?->id,
            'total' => 0,
            'estado_compra' => 'registrada',
            'observacion' => 'Compra inicial de productos',
            'state' => 'a',
        ]);
    }
}
