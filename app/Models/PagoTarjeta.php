<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoTarjeta extends Model
{
    use HasFactory;
    protected $table = 'pago_tarjeta';
    protected $fillable = [
        'idTicket',
        'montoTarjeta',
        'tipoTarjeta',
        'idBanco',
        'observacionesTarjeta',
        'cuatroDigitos',
        'mixto',
        'created_at',
        'uptdated_at',
    ];
}
