<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compra';
    protected $fillable = ['id_compra','id_producto','cantidad','precio_compra','subtotal','state'];

    public function compra(){ return $this->belongsTo(Compra::class, 'id_compra'); }
    public function producto(){ return $this->belongsTo(Producto::class, 'id_producto'); }

}
