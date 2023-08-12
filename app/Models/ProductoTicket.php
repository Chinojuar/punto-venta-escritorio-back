<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoTicket extends Model
{
    use HasFactory;
    protected $table = 'productos_ticket';
    protected $fillable = [
        'idProducto',
        'idTicket',
        'idMetodoPago',
        'precioVenta',
        'descuento',
        'observaciones',
        'cantidad',
    ];
}