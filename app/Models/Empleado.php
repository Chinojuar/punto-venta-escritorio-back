<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = 'empleados';

    protected $fillable = [
        'id_user',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'curp',
        'rfc',
        'nss',
        'direccion',
        'telefono',
        'correo_electronico',
        'puesto',
        'departamento',
        'fecha_inicio',
        'salario',
        'horas_laborales',
        'tipo_contrato',
        'fecha_alta',
        'fecha_baja',
        'baja',
        'fecha_reingreso'
    ];

   
}
