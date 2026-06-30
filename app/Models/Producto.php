<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = ['nombre','descripcion','categoria','marca','unidad_medida','precio_compra','precio_venta','codigo_qr','stock_minimo','state'];

    public function inventarios(){ return $this->hasMany(Inventario::class, 'id_producto'); }
    public function detalleCompras(){ return $this->hasMany(DetalleCompra::class, 'id_producto'); }
    public function detallePedidos(){ return $this->hasMany(DetallePedido::class, 'id_producto'); }

}
