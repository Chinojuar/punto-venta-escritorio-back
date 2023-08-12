<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carpeta;
use App\Models\Documento;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class CarpetasController extends Controller
{
    public function guardarCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idUsuario' => 'required',
            'nombre' => 'required',
            'idCarpetaPadre' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        Carpeta::create($request->all());

        $carpetas = Carpeta::with('documentos')->where('activo', 1)->get();

        return response()->json(
            Respuestas::respuesta200('Se creó una sucursal correctamente.', $carpetas),
            201
        );
    }

    public function actualizarCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'idUsuario' => 'nullable',
            'nombre' => 'nullable',
            'idCarpetaPadre' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombre' => $request->nombre,
            'idUsuario' => $request->idUsuario,
            'idCarpetaPadre' => $request->idCarpetaPadre,
        ];

        $datosActualizado = array_filter($datosActualizado);

        if ($request->has('idCarpetaPadre') && $request->idCarpetaPadre == null) {
            $datosActualizado = [
                'idCarpetaPadre' => null,
            ];
        }

        Carpeta::where('id', $request->input('id'))
            ->update($datosActualizado);

        $carpetas = Carpeta::with('documentos')->where('activo', 1)->get();

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $carpetas));
    }

    public function eliminarCarpeta($id)
    {
        if (!isset($id)) {
            return response()->json(Respuestas::respuesta400('No se envio el id de la carpeta'), 400);
        }

        Carpeta::where('id', $id)->update([
            'activo' => false,
        ]);

        Carpeta::where('idCarpetaPadre', $id)->update([
            'activo' => false,
        ]);

        Documento::where('id_carpeta', $id)->update([
            'activo' => false,
        ]);

        $carpetas = Carpeta::with('documentos')->where('activo', 1)->get();

        return response()->json(
            Respuestas::respuesta200('Se borro correctamente la carpeta.', $carpetas)
        );
    }

    public function consultarCarpetasDocumentos()
    {
        $respuesta = $this->consultaCarpetasDocumento();

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $respuesta
            )
        );
    }

    private function consultaCarpetasDocumento()
    {
        $carpetas = Carpeta::with('documentos')->where('activo', 1)->get();

        $documentos = Documento::where('activo', 1)->get();

        $actualizacion = Documento::latest('updated_at')
            ->where('activo', 1)
            ->join('users', 'documentos.id_user', '=', 'users.id')
            ->select('documentos.*', 'users.name AS nombreUsuario')
            ->first();

        return [
            'carpetas' => $carpetas,
            'documentos' => $documentos,
            'ultimaActualizacion' => $actualizacion
        ];
    }
}
