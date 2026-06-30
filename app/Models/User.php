<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre', 'apellido', 'ci', 'telefono', 'email', 'direccion', 'foto',
        'username', 'password', 'estilo', 'id_rol', 'state', 'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id');
    }

    public function comprasComoProveedor()
    {
        return $this->hasMany(Compra::class, 'id_proveedor', 'id');
    }

    public function pedidosComoCliente()
    {
        return $this->hasMany(Pedido::class, 'id_cliente', 'id');
    }

    public function entregasComoRepartidor()
    {
        return $this->hasMany(Entrega::class, 'id_repartidor', 'id');
    }

    public function getNameAttribute(): string
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellido ?? '')) ?: $this->email;
    }
}
