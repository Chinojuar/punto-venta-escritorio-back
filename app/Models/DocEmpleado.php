<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocEmpleado extends Model
{
    use HasFactory;
    protected $table = 'documentos_empleado';
    protected $fillable = [
        'id', 
        'id_emp',
        'nombre_archivo',
        'especificaciones',
        'uuid',
        'area',
        'extension',
        'activo',
        'estatus',
        'comentarios'
    ];


    public function getOrdenadosPorArea()
    {
        return $this->orderBy('area')->get();
    }
}