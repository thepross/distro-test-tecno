<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable = ['fecha_compra','id_proveedor','total','estado_compra','observacion','state'];

    public function proveedor(){ return $this->belongsTo(User::class, 'id_proveedor'); }
    public function detalles(){ return $this->hasMany(DetalleCompra::class, 'id_compra')->where('state', 'a'); }

}
