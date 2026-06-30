<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $fillable = ['fecha_pedido','id_cliente','total','estado_pedido','observacion','pagofacil_transaction_id','state'];

    public function cliente(){ return $this->belongsTo(User::class, 'id_cliente'); }
    public function detalles(){ return $this->hasMany(DetallePedido::class, 'id_pedido')->where('state', 'a'); }
    public function entregas(){ return $this->hasMany(Entrega::class, 'id_pedido'); }
    public function pagos(){ return $this->hasMany(Pago::class, 'id_pedido')->where('state', 'a'); }
    public function planPago(){ return $this->hasOne(PlanPago::class, 'id_pedido')->where('state', 'a'); }

}
