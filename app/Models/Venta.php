<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['estado', 'id_cliente', 'fecha_venta', 'state', 'total', 'id_usuario'];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_venta');
    }

    public function plan()
    {
        return $this->hasOne(PlanPago::class, 'id_venta');
    }

    public function planes()
    {
        return $this->hasMany(PlanPago::class, 'id_venta');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'id_venta');
    }

}
