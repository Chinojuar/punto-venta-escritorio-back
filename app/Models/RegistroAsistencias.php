<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroAsistencias extends Model
{
    use HasFactory;
    protected $table = 'registros_asistencia';

    protected $fillable = [
        'id',
        'id_emp',
        'estatus',
        'hora_entrada',
        'hora_salida',
        'fecha'
    ];

   
}
