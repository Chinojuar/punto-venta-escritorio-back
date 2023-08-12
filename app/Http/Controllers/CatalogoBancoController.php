<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatalogoBanco;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class CatalogoBancoController extends Controller
{
    public function consultarCatalogo()
    {
        $bancos = CatalogoBanco::orderBy('nombre')->get();
        return response()->json(
            Respuestas::respuesta200('Productos encontrados.', $bancos)
        );
    }
}
