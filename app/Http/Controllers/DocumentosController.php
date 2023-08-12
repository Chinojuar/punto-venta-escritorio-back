<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentosController extends Controller
{
    private $UUID;

    public function guardarArchivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file0' => 'required',
            'id_user' => 'int|required',
            'id_carpeta' => 'nullable',
            'nombre_archivo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }


        $datos_request = array_map('trim', $request->all());

        $archivo = $request->file('file0');
        $UUID = Str::orderedUuid();
        $extension = $archivo->getClientOriginalExtension();

        $documento = new Documento();
        $documento->id_user = $datos_request['id_user'];
        $documento->nombre_archivo = $datos_request['nombre_archivo'];
        $documento->id_carpeta = $datos_request['id_carpeta'];
        $documento->uuid = $UUID;
        $documento->extension = $extension;

        if ($request->id_carpeta == 'null')
            $documento->id_carpeta = null;

        // Guardar archivo en storage en disk documentos
        $archivo->storeAs('documentos', $UUID . '.' . $extension);

        // Guardar documento en base
        $documento->save();

        $documentos = Documento::where('activo', 1)->get();

        $actualizacion = Documento::latest('updated_at')
            ->where('activo', 1)
            ->join('users', 'documentos.id_user', '=', 'users.id')
            ->select('documentos.*', 'users.name AS nombreUsuario')
            ->first();

        $respuesta = [
            'documentos' => $documentos,
            'ultimaActualizacion' => $actualizacion
        ];

        return response()->json(Respuestas::respuesta200('Archivo guardado.', $respuesta));
    }

    public function traerArchivo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'string|required',
            'extension' => 'string|required',
            'area' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $UUID = $request->input('uuid');
        $extension = $request->input('extension');
        $area = $request->input('area');

        return Storage::disk('documentos')->get($area . '/' . $UUID . "." . $extension);
    }

    public function descargarArchivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'string|required',
            'extension' => 'string|required',
            'area' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $UUID = $request->input('uuid');
        $extension = $request->input('extension');
        $area = $request->input('area');

        return Storage::disk('documentos')->download($area . '/' . $UUID . "." . $extension);
    }

    public function traerTodosDocumentos()
    {
        /**
         *  Método para consultaer todos los documentos ordenados alfabeticamente por área
         */
        $documentos = Documento::where('activo', 1)
            ->join('areas', 'documentos.id_area', '=', 'areas.id')
            ->select('documentos.*', 'areas.area')
            ->orderBy('area')
            ->get();

        $actualizacion = Documento::latest('updated_at')
            ->where('activo', 1)
            ->join('users', 'documentos.id_user', '=', 'users.id')
            ->select('documentos.*', 'users.name AS nombreUsuario')
            ->first();

        $respuesta = [
            'documentos' => $documentos,
            'ultimaActualizacion' => $actualizacion
        ];

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $respuesta
            )
        );
    }

    public function traerDocumentosArea($area)
    {
        /**
         *  Método para consultaer todos los documentos de una área
         */

        if (!$area) {
            return response()->json(Respuestas::respuesta400('No se tiene el área a buscar.'));
        }

        $documentos = Documento::where('area', $area)->where('activo', true)->get();

        if (count($documentos) < 1) {
            return response()->json(Respuestas::respuesta400('El área no se encontro.'));
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $documentos
            )
        );
    }

    public function actualizarInfoDocumento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'id_user' => 'int|nullable',
            'nombre_archivo' => 'string|nullable',
            'id_carpeta' => 'int|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id_user' => $request->id_user,
            'nombre_archivo' => $request->nombre_archivo,
            'id_carpeta' => $request->id_carpeta,
        ];

        $datosActualizado = array_filter($datosActualizado);

        if ($request->has('id_carpeta') && $request->id_carpeta == null) {
            $datosActualizado = [
                'id_carpeta' => null,
            ];
        }

        Documento::where('id', $request->input('id'))
            ->update($datosActualizado);

        $documentos = Documento::where('activo', 1)->get();

        $actualizacion = Documento::latest('updated_at')
            ->where('activo', 1)
            ->join('users', 'documentos.id_user', '=', 'users.id')
            ->select('documentos.*', 'users.name AS nombreUsuario')
            ->first();

        $respuesta = [
            'documentos' => $documentos,
            'ultimaActualizacion' => $actualizacion
        ];

        return response()->json(Respuestas::respuesta200('Se cambio correctamente el nombre.', $respuesta));
    }

    public function actualizarDocumento(Request $request)
    {
        /**
         *  Método para actualizar un documento
         */

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'id_user' => 'int|nullable',
            'nombre_archivo' => 'string|nullable',
            'uuid' => 'string|nullable',
            'file0' => 'nullable',
            'extension' => 'string|nullable',
            'id_area' => 'int|nullable',
            'area' => 'string|nullable',
            'areaNueva' => 'string|nullable',
            'areaAnterior' => 'string|nullable',
            'activo' => 'boolean|nullable',
        ]);

        $extensionNueva = '';

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        if (
            $request->has('file0') &&
            $request->has('extension') &&
            $request->has('area') &&
            $request->has('uuid')
        ) {
            // CASO1: Se actualiza el documento y el archivo
            Storage::delete('documentos/' . $request->area . '/' . $request->uuid . '.' . $request->extension);

            $archivo = $request->file('file0');
            $area = $request->input('area');
            $extensionNueva = $archivo->getClientOriginalExtension();
            $this->UUID = Str::orderedUuid();

            $archivo->storeAs(
                "/" . $area,
                $this->UUID . '.' . $extensionNueva,
                'documentos'
            );
        } elseif ($request->has('areaNueva')) {
            // CASO 2: Se actualiza el area
            Storage::move(
                'documentos/' . $request->areaAnterior . '/' . $request->uuid . '.' .
                $request->extension,
                'documentos/' . $request->area . '/' .
                $request->uuid . '.' . $request->extension
            );
        } else {
            // CASO 3: Se actualiza lo demás
            $this->UUID = $request->uuid;
        }

        $datosActualizado = [
            'id_user' => $request->id_user,
            'nombre_archivo' => $request->nombre_archivo,
            'uuid' => $this->UUID,
            'extension' => $extensionNueva,
            'id_area' => $request->areaNueva | $request->id_area,
            'activo' => $request->activo,
        ];

        $datosActualizado = array_filter($datosActualizado);

        if ($request->has('activo')) {
            $datosActualizado = [
                'activo' => false,
            ];
        }

        Documento::where('id', $request->input('id'))
            ->update($datosActualizado);

        $documentoRespuesta = Documento::where('id', $request->input('id'))->get();

        return response()->json(Respuestas::respuesta200('Se actualizó el documento.', $documentoRespuesta[0]));
    }

    public function borrarDocumento($id)
    {
        /**
         *  Método para borrar un documento
         */
        if (!isset($id)) {
            return response()->json(Respuestas::respuesta400('No se envío id del documento'), 400);
        }

        $datosActualizado = [
            'activo' => false,
        ];

        Documento::where('id', $id)->update($datosActualizado);

        $documentos = Documento::where('activo', 1)->get();

        $actualizacion = Documento::latest('updated_at')
            ->where('activo', 1)
            ->join('users', 'documentos.id_user', '=', 'users.id')
            ->select('documentos.*', 'users.name AS nombreUsuario')
            ->first();

        $respuesta = [
            'documentos' => $documentos,
            'ultimaActualizacion' => $actualizacion
        ];

        return response()->json(Respuestas::respuesta200('Se elimino el documento correctamente', $respuesta));
    }

    public function descargarDocumento($uuid, $extension)
    {
        /**
         *  Método para borrar un documento
         */

        $documento = Documento::where('uuid', $uuid)->first();

        if (!$uuid) {
            return response()->json(Respuestas::respuesta400('No se tiene uuid'));
        }

        $ruta = '/documentos/' . $uuid . '.' . $extension;
        return Storage::download(
            $ruta,
            $documento->nombre_archivo .
            '.' .
            $documento->extension
        );
    }
}