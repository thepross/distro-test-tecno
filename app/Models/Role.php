<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'descripcion', 'state'];

    public function privilegios()
    {
        return $this->hasMany(Privilegio::class, 'id_rol', 'id');
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_rol', 'id');
    }
}
