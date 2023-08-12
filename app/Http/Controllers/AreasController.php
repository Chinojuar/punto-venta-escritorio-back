<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Respuestas\Respuestas;

class AreasController extends Controller
{
    public function consultarAreas()
    {
        $areas = Area::all();

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $areas
            )
        );
    }
}