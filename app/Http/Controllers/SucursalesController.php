<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class SucursalesController extends Controller
{
    public function crearSucursal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idEncargado' => 'required',
            'nombreSucursal' => 'required',
            'telefono' => 'required',
            'correo' => 'required',
            'horarioAtencion' => 'required',
            'domicilio' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        Sucursal::create($request->all());

        $sucursales = Sucursal::join('empleados', 'empleados.id', '=', 'sucursales.idEncargado')
            ->select(
                'sucursales.*',
                'empleados.nombres AS nombreEncargado',
                'empleados.apellido_paterno AS apellidoPaternoEncargado',
                'empleados.apellido_materno AS apellidoMaternoEncargado'
            )
            ->get();

        return response()->json(
            Respuestas::respuesta200('Se creó una sucursal correctamente.', $sucursales),
            201
        );
    }

    public function actualizarSucursal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombreSucursal' => 'nullable',
            'idEncargado' => 'nullable',
            'telefono' => 'nullable',
            'correo' => 'nullable',
            'horarioAtencion' => 'nullable',
            'domicilio' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombreSucursal' => $request->nombreSucursal,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'horarioAtencion' => $request->horarioAtencion,
            'domicilio' => $request->domicilio,
            'idEncargado' => $request->idEncargado,
        ];

        $datosActualizado = array_filter($datosActualizado);

        Sucursal::where('id', $request->input('id'))
            ->update($datosActualizado);

        $sucursales = Sucursal::join('empleados', 'empleados.id', '=', 'sucursales.idEncargado')
            ->select(
                'sucursales.*',
                'empleados.nombres AS nombreEncargado',
                'empleados.apellido_paterno AS apellidoPaternoEncargado',
                'empleados.apellido_materno AS apellidoMaternoEncargado'
            )
            ->get();

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $sucursales));
    }

    public function consultarSucursales()
    {
        $sucursales = Sucursal::join('empleados', 'empleados.id', '=', 'sucursales.idEncargado')
            ->select(
                'sucursales.*',
                'empleados.nombres AS nombreEncargado',
                'empleados.apellido_paterno AS apellidoPaternoEncargado',
                'empleados.apellido_materno AS apellidoMaternoEncargado'
            )
            ->get();

        if (count($sucursales) < 1) {
            return response()->json(Respuestas::respuesta400('No se encontrarón resultados'), 400);
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $sucursales
            )
        );
    }

    public function eliminarSucursal($id)
    {
        $sucursal = Sucursal::find($id);

        if (!$sucursal) {
            return response()->json(Respuestas::respuesta404('sucursal no encontrada'));
        }

        $sucursal->delete();

        $sucursales = Sucursal::join('empleados', 'empleados.id', '=', 'sucursales.idEncargado')
            ->select(
                'sucursales.*',
                'empleados.nombres AS nombreEncargado',
                'empleados.apellido_paterno AS apellidoPaternoEncargado',
                'empleados.apellido_materno AS apellidoMaternoEncargado'
            )
            ->get();

        return response()->json(Respuestas::respuesta200('sucursal eliminada.', $sucursales));
    }
}
