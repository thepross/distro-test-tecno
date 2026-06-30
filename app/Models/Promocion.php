<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = ['descuento','fecha_inicio','fecha_fin','state','descripcion', 'nombre'];
}
