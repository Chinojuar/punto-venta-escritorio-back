<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    use HasFactory;
    protected $table = 'calendario_personal';
    protected $fillable = [
        'idUsuario',
        'tipo',
        'contenido',
        'fecha',
        'resuelto',
    ];
}
