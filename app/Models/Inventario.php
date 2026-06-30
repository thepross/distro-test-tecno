<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $fillable = ['id_producto','tipo_movimiento','cantidad','fecha_movimiento','stock_actual','descripcion','state'];

    public function producto(){ return $this->belongsTo(Producto::class, 'id_producto'); }

}
