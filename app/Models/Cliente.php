<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['nombre', 'direccion', 'telefono', 'id_user', 'state'];

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'id_user');
    }

    public function ventas()
{
    return $this->hasMany(Venta::class);
}
}
