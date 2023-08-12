<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlmacenCompras extends Model
{
    use HasFactory;
    protected $table = 'almacen_compras';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigoBarras',
        'sku',
        'categoria',
        'compradosTotales',
        'cantidadStock',
        'agotado',
        'eliminado',
        'stockCompras',
    ];
}
