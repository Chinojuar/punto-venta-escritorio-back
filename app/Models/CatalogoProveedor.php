<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoProveedor extends Model
{
    use HasFactory;
    protected $table = 'catalogo_proveedores';
    protected $fillable = [
        'idProducto',
        'idProveedor',
        'precioCompra',
        'precioMaximoVenta',
        'politicasVenta',
    ];
}
