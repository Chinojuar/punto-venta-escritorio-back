<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function consultarTodosProveedores()
    {
        $proveedores = DB::table('proveedores')->get();
        $actualizacion = Proveedor::latest('updated_at')->first();
        $respuesta = [
            'proveedores' => $proveedores,
            'utlimaActualizacion' => $actualizacion
        ];

        return response()->json(Respuestas::respuesta200('Proveedores encontrados.', $respuesta));
    }

    public function crearProveedor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'telefono' => 'required',
            'correo' => 'required',
            'domicilio' => 'required',
            'representante' => 'required',
            'sitioWeb' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        if ($request->sitioWeb == null) {
            $request->merge(['sitioWeb' => 'Sin sitio web']);
        }

        $proveedor = Proveedor::create($request->all());

        return response()->json(Respuestas::respuesta200('Proveedor creado.', $proveedor), 201);
    }

    public function consultarProveedor($id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json(Respuestas::respuesta404('proveedor no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('proveedor encontrado.', $proveedor));
    }

    public function actualizarProveedor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'nullable',
            'telefono' => 'nullable',
            'correo' => 'nullable',
            'domicilio' => 'nullable',
            'sitioWeb' => 'nullable',
            'representante' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'domicilio' => $request->domicilio,
            'sitioWeb' => $request->sitioWeb,
            'representante' => $request->representante,
        ];

        $datosActualizado = array_filter($datosActualizado);

        Proveedor::where('id', $request->input('id'))
            ->update($datosActualizado);

        return response()->json(Respuestas::respuesta200NoResultados('Producto actualizado.'));
    }

    public function eliminarProveedor($id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json(Respuestas::respuesta404('proveedor no encontrado'));
        }

        $proveedor->delete();

        return response()->json(Respuestas::respuesta200NoResultados('proveedor eliminado'));
    }
}