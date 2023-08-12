<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'tickets';
    protected $fillable = [
        'idMetodoPago',
        'idUsuario',
        'idSucursal',
        'idCliente',
        'cantidadArticulos',
        'iva',
        'total',
        'created_at',
        'updated_at'
    ];
}