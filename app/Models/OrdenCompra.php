<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;
    protected $table = 'orden_compra_info';
    protected $fillable = [
        'id',
        'idProveedor',
        'representanteVendedor',
        'telefonoVendedor',
        'correoVendedor',
        'direccionVendedor',
        'representanteComprador',
        'telefonoComprador',
        'correoComprador',
        'direccionComprador',
        'instruccionEspecial',
        'subtotal',
        'descuento',
        'otros',
        'iva',
        'total',
    ];
}
