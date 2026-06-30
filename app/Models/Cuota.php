<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';
    protected $fillable = ['id_plan_pago','numero_cuota','fecha_vencimiento','monto','estado_cuota','fecha_pago','pagofacil_transaction_id','state'];

    public function planPago(){ return $this->belongsTo(PlanPago::class, 'id_plan_pago'); }

}
