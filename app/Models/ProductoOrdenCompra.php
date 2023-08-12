<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoOrdenCompra extends Model
{
    use HasFactory;
    protected $table = 'productos_orden_compra';
    protected $fillable = [
        'id',
        'idOrdenCompra',
        'idProveedor',
        'idProducto',
        'precioCompra',
        'cantidadCompra',
        'descuento',
    ];
}
