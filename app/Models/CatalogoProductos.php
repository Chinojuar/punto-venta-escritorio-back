<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoProductos extends Model
{
    use HasFactory;
    protected $table = 'catalogo_productos';

    protected $fillable = [
        'nombreProducto',
        'descripcion',
        'codigoBarras',
        'sku',
        'idCategoria',
        'ventasTotales',
        'cantidadStock',
        'agotado',
        'precioVenta',
        'precioCompra',
        'eliminado',
        'stockCompras',
    ];
}
