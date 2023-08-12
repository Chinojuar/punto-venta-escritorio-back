<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoBanco extends Model
{
    use HasFactory;
    protected $table = 'catalogo_bancos';
    protected $fillable = [
        'nombre',
    ];
}
