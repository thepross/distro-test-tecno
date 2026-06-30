<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = ['descripcion', 'duracion', 'id_promocion', 'nombre', 'precio', 'state'];

    public function promocion()
    {
        return $this->belongsTo(Promocion::class, 'id_promocion');
    }

    public function precioConDescuento()
    {
        if ($this->promocion) {
            $descuento = $this->promocion->descuento;  // Asumimos que el descuento es un porcentaje
            return $this->precio - ($this->precio * $descuento / 100);
        }
        return $this->precio; // Si no tiene descuento, devolver el precio original
    }
}
