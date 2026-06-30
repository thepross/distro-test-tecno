<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas';
    protected $fillable = ['id_pedido','id_repartidor','fecha_salida','fecha_entrega','direccion_entrega','estado_entrega','observacion','state'];

    public function pedido(){ return $this->belongsTo(Pedido::class, 'id_pedido'); }
    public function repartidor(){ return $this->belongsTo(User::class, 'id_repartidor'); }

}
