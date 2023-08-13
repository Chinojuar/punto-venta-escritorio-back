<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use App\Models\Empleado;
use Illuminate\Support\Facades\Storage;
use App\Models\DocEmpleado;
use App\Models\RegistroAsistencias;
use Illuminate\Support\Str;

class EmpleadoController extends Controller
{
    private $UUID;

    public function consultarTodosEmpleados()
    {
        $empleados = Empleado::orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->get();
        return response()->json(Respuestas::respuesta200('Empleados encontrados.', $empleados));
    }

    public function crearEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user'=>'required',
            'nombres' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'fecha_nacimiento' => 'nullable',
            'genero' => 'required',
            'estado_civil' => 'nullable',
            'curp' => 'nullable',
            'rfc' => 'nullable',
            'nss' => 'nullable',
            'telefono' => 'required',
            'correo_electronico' => 'required',
            'salario' => 'nullable',
            'horario' => 'nullable',
            'tipo_contrato' => 'nullable',
            'imagen' => 'nullable',
            'calle' => 'required',
            'numeroExt' => 'required',
            'numeroInt' => 'nullable',
            'colonia' => 'required',
            'codigoPostal' => 'required',
            'delegacion' => 'required',
            'ciudad' => 'nullable',
            'referencias' => 'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }


        if( $request->has('imagen')){
            $archivo = $request->file('imagen');
            $UUID = Str::orderedUuid();
            $extension = $archivo->getClientOriginalExtension();
        }

      $empleado = Empleado::create($request->all());

      $empleado-> imagen = $UUID;
      $empleado-> extension = $extension;

      if($request->hasFile('file0')){
        $archivo->storeAs(
            $UUID . '.' . $extension,
            'empleados'
        );
    }

       $empleados = Empleado::orderBy('apellido_paterno')
       ->orderBy('apellido_materno')
       ->orderBy('nombres')
       ->get();

        return response()->json(Respuestas::respuesta200('Empleado creado.', $empleados), 201);
    }

    public function consultarEmpleado($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(Respuestas::respuesta404('Empleado no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('Empleado encontrado.', $empleado));
    }

    public function actualizarEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'id_user' => 'required',
            'nombres' => 'nullable',
            'apellido_paterno' => 'nullable',
            'apellido_materno' => 'nullable',
            'fecha_nacimiento' => 'nullable',
            'genero' => 'nullable',
            'estado_civil' => 'nullable',
            'curp' => 'nullable',
            'rfc' => 'nullable',
            'nss' => 'nullable',
            'direccion' => 'nullable',
            'telefono' => 'nullable',
            'correo_electronico' => 'nullable',
            'puesto' => 'nullable',
            'departamento' => 'nullable',
            'fecha_inicio' => 'nullable',
            'salario' => 'nullable',
            'horas_laborales' => 'nullable',
            'tipo_contrato' => 'nullable',
            'fecha_alta' => 'nullable',
            'fecha_baja' => 'nullable',
            'baja' => 'nullable',
            'hora_entrada' => 'nullable',
            'hora_salida' => 'nullable',
            'asistencia' => 'nullable',
            'fecha_reingreso' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizados = [
            'id' => $request->id,
            'id_user' => $request->id_user,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'estado_civil' => $request->estado_civil,
            'curp' => $request->curp,
            'rfc' => $request->rfc,
            'nss' => $request->nss,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo_electronico' => $request->correo_electronico,
            'puesto' => $request->puesto,
            'departamento' => $request->departamento,
            'fecha_inicio' => $request->fecha_inicio,
            'salario' => $request->salario,
            'horas_laborales' => $request->horas_laborales,
            'tipo_contrato' => $request->tipo_contrato,
            'fecha_alta' => $request->fecha_alta,
            'fecha_baja' => $request->fecha_baja,
            'baja' => $request->baja,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'asistencia' => $request->asistencia,
            'fecha_reingreso' => $request->fecha_reingreso,
        ];

        $datosActualizados = array_filter($datosActualizados);
        if ($request->baja == 0) {
            $datosActualizados['baja'] = 0;
        }
        
        Empleado::where('id', $request->input('id'))
            ->update($datosActualizados);

            $empleados = Empleado::orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->get();
     



        return response()->json(Respuestas::respuesta200('Empleado actualizado.',$empleados),201);
    }


    public function eliminarEmpleado($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(Respuestas::respuesta404('Empleado no encontrado'));
        }

        $empleado->delete();

        $empleados = Empleado::orderBy('apellido_paterno')
        ->orderBy('apellido_materno')
        ->orderBy('nombres')
        ->get();

        return response()->json(Respuestas::respuesta200('Empleado eliminado',$empleados),201);
    }

    public function guardarArchivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_emp' => 'int|required',
            'file0' => 'nullable',
            'area' => 'required',
            'nombre_archivo' => 'required',
            'especificaciones'  => 'nullable',
            'estatus' => 'nullable',
            'comentarios' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }


        $datos_request = array_map('trim', $request->all());

        $archivo = $request->file('file0');
        $area = $request->input('area');
        $comentarios = $request->input('comentarios');
        $especificaciones = $request->input('especificaciones');
        $estatus = $request->input('estatus');
        if($request->hasFile('file0')){
        $UUID = Str::orderedUuid();
        $extension = $archivo->getClientOriginalExtension();
        }
       

        $documento = new DocEmpleado();
        $documento->id_emp = $datos_request['id_emp'];
        $documento->nombre_archivo = $datos_request['nombre_archivo'];
        $documento->especificaciones = $datos_request['especificaciones'];
        $documento->area = $datos_request['area'];
        $documento->comentarios = $datos_request['comentarios'];
        $documento->estatus = $datos_request['estatus'];
        if($request->hasFile('file0')){
        $documento->uuid = $UUID;
        $documento->extension = $extension;
        }
        $documento->save();

        if($request->hasFile('file0')){
        $archivo->storeAs(
            "/" . $area,
            $UUID . '.' . $extension,
            'empleados'
        );
    }
    
        $documentoRespuesta = DocEmpleado::orderBy('id')->get();

        return response()->json(Respuestas::respuesta200('Archivo guardado.', $documentoRespuesta));
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

        return Storage::disk('empleados')->get($area . '/' . $UUID . "." . $extension);
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

        return Storage::disk('empleados')->download($area . '/' . $UUID . "." . $extension);
    }

    public function traerTodosDocumentos()
    {
        /**
         *  Método para consultaer todos los documentos ordenados alfabeticamente por área
         */
        $documentos = new DocEmpleado;
        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $documentos->getOrdenadosPorArea()
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

        $documentos = DocEmpleado::where('area', $area)->where('activo', true)->get();

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

    public function actualizarDocumento(Request $request)
    {
        /**
         *  Método para actualizar un documento
         */

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'id_emp' => 'int|nullable',
            'nombre_archivo' => 'string|nullable',
            'uuid' => 'string|nullable',
            'file0' => 'nullable',
            'extension' => 'string|nullable',
            'area' => 'string|nullable',
            'areaNueva' => 'string|nullable',
            'activo' => 'boolean|nullable',
            'estatus' => 'string|nullable',
            'comentarios' => 'string|nullable'
        ]);

        $extensionNueva = '';

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        if(
            //CASO1: Se actualiza el documento y  el archivo
            $request->has('file0') &&
            $request->has('extension') &&
            $request->has('area') &&
            $request->has('uuid')
        ){

            
            Storage::delete('empleados/' . $request->area . '/' . $request->uuid . '.' . $request->extension);

            $archivo = $request->file('file0');
            $area = $request->input('area');
            $extensionNueva = $archivo->getClientOriginalExtension();
            $this->UUID = Str::orderedUuid();

            DocEmpleado::where('area', $request->area)
            ->where('uuid', $request->uuid)
            ->update(['comentarios' => ""]);


            $archivo->storeAs(
                "/" . $area,
                $this->UUID . '.' . $extensionNueva,
                'empleados'
            );


        }elseif (
                $request->has('file0') 
            ) {
                // CASO0: Se actualiza el documento y se crea el archivo
    
                $archivo = $request->file('file0');
                $area = $request->input('area');
                $extensionNueva = $archivo->getClientOriginalExtension();
                $this->UUID = Str::orderedUuid();
    
                $archivo->storeAs(
                    "/" . $area,
                    $this->UUID . '.' . $extensionNueva,
                    'empleados'
                );
    
        }
         elseif ($request->has('areaNueva')) {
            // CASO 2: Se actualiza el area
            Storage::move(
                'empleados/' . $request->area . '/' . $request->uuid . '.' .
                    $request->extension,
                'empleados/' . $request->areaNueva . '/' .
                    $request->uuid . '.' . $request->extension
            );
        } else {
            // CASO 3: Se actualiza lo demás
            $this->UUID = $request->uuid;
        }

        $datosActualizado = [
            'id_emp' => $request->id_emp,
            'nombre_archivo' => $request->nombre_archivo,
            'uuid' => $this->UUID,
            'extension' => $extensionNueva,
            'area' => $request->areaNueva,
            'activo' => $request->activo,
            'estatus' => $request->estatus,
            'comentarios' => $request->comentarios,
        ];

        $datosActualizado = array_filter($datosActualizado);


        if ($request->has('activo')) {
            $datosActualizado = [
                'activo' => false,
            ];
        }

        DocEmpleado::where('id', $request->input('id'))
            ->update($datosActualizado);

            $documentoRespuesta = DocEmpleado::orderBy('id')->get();

        return response()->json(Respuestas::respuesta200('Se actualizó el documento.', $documentoRespuesta));
    }

    public function borrarDocumento(Request $request)
    {
        /**
         *  Método para borrar un documento
         */

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'activo' => false,
        ];
        DocEmpleado::where('id', $request->input('id'))
            ->update($datosActualizado);

        return response()->json(Respuestas::respuesta200NoResultados('Se borro correctamente el documento.'));
    }

    public function descargarDocumento($uuid, $extension, $area, $nombre_archivo)
    {
        /**
         *  Método para borrar un documento
         */

        if (!$uuid) {
            return response()->json(Respuestas::respuesta400('No se tiene uuid'));
        }

        $ruta = '/empleados/' . $area . '/' . $uuid . '.' . $extension;
        return Storage::download(
            $ruta,
            $nombre_archivo .
                '.' .
                $extension
        );
    }

    public function guardarAsistencia(Request $request){

        $validator = Validator::make($request->all(), [
            'id_emp' => 'int|required',
            'fecha' => 'nullable',
            'estatus' => 'nullable',
            'hora_entrada' => 'nullable',
            'hora_salida' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $registro = new RegistroAsistencias();
        $registro->id_emp = $request->input('id_emp');
        $registro->fecha = $request->input('fecha');
        $registro->hora_entrada = $request->input('hora_entrada');
       
    
        $registro->save();
    
        $documentoRespuesta = RegistroAsistencias::find($registro->id);

        return response()->json(Respuestas::respuesta200('Archivo guardado.', $documentoRespuesta));
    }

    public function actualizarAsistencia(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'id_emp' => 'nullable',
            'fecha' => 'nullable',
            'estatus' => 'nullable',
            'hora_entrada' => 'nullable',
            'hora_salida' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizados = [
            'id' => $request->id,
            'hora_salida' => $request->hora_salida
        ];

        $datosActualizados = array_filter($datosActualizados);
        
        
        RegistroAsistencias::where('id', $request->input('id'))
            ->update($datosActualizados);

            $registrosRespuesta = RegistroAsistencias::find($request->input('id'));

            return response()->json(Respuestas::respuesta200('Registro Actualizado.', $registrosRespuesta,201));
    }

    public function traerRegistroAsistencia($id_emp, $dia, $mes, $anio)
    {
        
        $registrosRespuesta = RegistroAsistencias::where([
            'id_emp' => $id_emp,
            'fecha' => "$dia/$mes/$anio", 
        ])->get();
    
        if (count($registrosRespuesta) < 1) {
            return response()->json(Respuestas::respuesta400('El registro no se encontro.'));
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $registrosRespuesta[0]
            )
        );
    }

    }

