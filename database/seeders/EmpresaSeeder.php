<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::updateOrCreate(['id' => 1], [
            'nombre' => 'Distribuidora Carla',
            'direccion' => 'Av. Principal N° 123',
            'telefono' => 70000000,
            'correo' => 'contacto@distribuidoracarla.com',
            'state' => 'a',
        ]);
    }
}
