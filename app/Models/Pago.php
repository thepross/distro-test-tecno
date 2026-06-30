<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $fillable = ['id_pedido','fecha_pago','monto','tipo_pago','estado_pago','observacion','state'];

    public function pedido(){ return $this->belongsTo(Pedido::class, 'id_pedido'); }

}
