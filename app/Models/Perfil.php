<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;
    protected $table = 'empleados';
    protected $fillable = [
        'id',
        'id_usuario',
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'fechaNacimiento',
        'genero',
        'edad',
        'curp',
        'rfc',
        'nss',
        'numeroTelefonico',
        'numeroEmpleado',
        'salario',
        'email',
        'puesto',
        'area',
        'jefeDirecto',
        'regimenFiscal',
        'calle',
        'numeroExterior',
        'numeroInterior',
        'colonia',
        'municipio',
        'estado',
        'codigoPostal',
        'activo'
    ];
}
