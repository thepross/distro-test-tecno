<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table = 'plan_pago';
    protected $fillable = ['id_pedido','cantidad_cuotas','monto_cuota','total_deuda','saldo_pendiente','fecha_inicio','estado_plan','state'];

    public function pedido(){ return $this->belongsTo(Pedido::class, 'id_pedido'); }
    public function cuotas(){ return $this->hasMany(Cuota::class, 'id_plan_pago')->where('state', 'a')->orderBy('numero_cuota'); }

}
