<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendario;
use Carbon\Carbon;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class CalendarioController extends Controller
{
    public function crearEventoCalendario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idUsuario' => 'required',
            'fecha' => 'required',
            'tipo' => 'required',
            'contenido' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $eventoCalendario = Calendario::create($request->all());

        return response()->json(Respuestas::respuesta200('Producto agregado a catalogo.', $eventoCalendario), 201);
    }
    public function consultarCalendarioUsuario($ano, $idUsuario)
    {
        $registros = Calendario::where('idUsuario', $idUsuario)
            ->whereYear('fecha', $ano)
            ->get();

        if (count($registros) < 1) {
            return response()->json(Respuestas::respuesta400('No se encontrarón resultados'), 400);
        }

        $listDataMap = [];

        foreach ($registros as $registro) {
            $dia = Carbon::parse($registro->fecha)->day;
            $mes = Carbon::parse($registro->fecha)->month;
            $ano = Carbon::parse($registro->fecha)->year;

            if (!isset($listDataMap[$dia])) {
                $listDataMap[$dia] = [];
            }

            $listDataMap[$dia][] = [
                'id' => $registro->id,
                'idUsuario' => $registro->idUsuario,
                'tipo' => $registro->tipo,
                'contenido' => $registro->contenido,
                'dia' => $dia,
                'mes' => $mes,
                'ano' => $ano,
                'resuelto' => $registro->resuelto,
            ];
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $listDataMap
            )
        );
    }

    public function eliminarEventoCalendarioUsuario($id)
    {
        $evento = Calendario::find($id);

        if (!$evento) {
            return response()->json(Respuestas::respuesta404('evento no encontrado'));
        }

        $evento->delete();

        return response()->json(Respuestas::respuesta200NoResultados('evento eliminado del catálogo.'));
    }
}
