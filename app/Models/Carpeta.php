<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    use HasFactory;
    protected $table = 'carpetas';
    protected $fillable = [
        'idUsuario',
        'idCarpetaPadre',
        'nombre',
        'activo'
    ];

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_carpeta', 'id');
    }
}
