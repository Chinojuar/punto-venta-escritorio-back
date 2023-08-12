<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoVales extends Model
{
    use HasFactory;
    protected $table = 'pago_vales';
    protected $fillable = [
        'idTicket',
        'montoVale',
        'codigoVale',
        'observacionesVale',
        'mixto',
        'created_at',
        'uptdated_at',
    ];
}
