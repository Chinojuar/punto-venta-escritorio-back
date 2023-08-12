<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoEfectivo extends Model
{
    use HasFactory;
    protected $table = 'pago_efectivo';
    protected $fillable = [
        'idTicket',
        'montoEfectivo',
        'cambioDevuelto',
        'observacionesEfectivo',
        'mixto',
        'created_at',
        'uptdated_at',
    ];
}
