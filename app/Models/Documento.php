<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $table = 'documentos';
    protected $fillable = [
        'id', 
        'id_user',
        'id_carpeta',
        'nombre_archivo',
        'uuid',
        'id_area',
        'extension',
        'activo'
    ];


    public function getOrdenadosPorArea()
    {
        return $this->where('activo', 1)->orderBy('area')->get();
    }

    public function carpeta()
    {
        return $this->belongsTo(Carpeta::class, 'id_carpeta', 'id');
    }
}
