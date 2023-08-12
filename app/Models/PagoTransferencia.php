<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoTransferencia extends Model
{
    use HasFactory;
    protected $table = 'pago_transferencia';
    protected $fillable = [
        'idTicket',
        'montoTransferencia',
        'idBanco',
        'observacionesTransferencia',
        'mixto',
        'created_at',
        'uptdated_at',
    ];
}
