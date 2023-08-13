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
        'telefono',
        'correo_electronico',
        'salario',
        'horario',
        'tipo_contrato',
        'fecha_alta',
        'fecha_baja',
        'baja',
        'fecha_reingreso',
        'imagen',
        'calle',
        'numeroExt',
        'numeroInt',
        'colonia',
        'codigoPostal',
        'delegacion',
        'ciudad',
        'referencias',
    ];

   
}
