<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaCatalogoProductos extends Model
{
    use HasFactory;
    protected $table = 'categorias_catalogo_productos';
    protected $fillable = [
        'id', 
        'categoria',
    ];
}
