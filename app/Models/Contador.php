<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $table = 'contadors';
    protected $fillable = ['nombre', 'visitas', 'tipo'];

    private array $modulos = [
        1 => 'Roles',
        2 => 'Usuarios',
        3 => 'Productos',
        4 => 'Compras',
        5 => 'Detalle de compras',
        6 => 'Inventario',
        7 => 'Pedidos',
        8 => 'Detalle de pedidos',
        9 => 'Entregas',
        10 => 'Pagos',
        11 => 'Plan de pago',
        12 => 'Cuotas',
        13 => 'Reportes',
        14 => 'Privilegios',
        15 => 'Configuración',
    ];

    public function contarModel($id)
    {
        $contar = self::firstOrCreate(
            ['id' => $id],
            ['nombre' => $this->modulos[$id] ?? 'Módulo '.$id, 'visitas' => 0, 'tipo' => $id]
        );

        if (($this->modulos[$id] ?? null) && $contar->nombre !== $this->modulos[$id]) {
            $contar->nombre = $this->modulos[$id];
            $contar->save();
        }

        $contar->increment('visitas');
        return $contar->visitas;
    }
}
