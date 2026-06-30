<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['estado', 'fecha_hora', 'id_cliente', 'id_medico', 'id_secretaria', 'id_servicio', 'state'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function medico()
    {
        return $this->belongsTo(Usuario::class, 'id_medico');
    }

    public function secretaria()
    {
        return $this->belongsTo(Usuario::class, 'id_secretaria');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function scopeActivas($query)
    {
        return $query->where('state', 'a');
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

}
