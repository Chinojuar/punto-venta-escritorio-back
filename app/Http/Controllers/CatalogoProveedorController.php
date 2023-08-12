<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use App\Models\CatalogoProveedor;

class CatalogoProveedorController extends Controller
{
    public function crearProductoCatalogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idProveedor' => 'required',
            'idProducto' => 'required',
            'precioCompra' => 'required',
            'precioMaximoVenta' => 'required',
            'politicasVenta' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $productoCatalogo = CatalogoProveedor::create($request->all());

        return response()->json(Respuestas::respuesta200('Producto agregado a catalogo.', $productoCatalogo), 201);
    }

    public function actualizarProductoCatalogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'idProveedor' => 'nullable',
            'idProducto' => 'nullable',
            'precioCompra' => 'nullable',
            'precioMaximoVenta' => 'nullable',
            'politicasVenta' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'idProveedor' => $request->idProveedor,
            'idProducto' => $request->idProducto,
            'precioCompra' => $request->precioCompra,
            'precioMaximoVenta' => $request->precioMaximoVenta,
            'politicasVenta' => $request->politicasVenta,
        ];

        $datosActualizado = array_filter($datosActualizado);

        CatalogoProveedor::where('id', $request->input('id'))
            ->update($datosActualizado);

        return response()->json(Respuestas::respuesta200NoResultados('Producto de catálogo actualizado.'));
    }

    public function consultarCatalogoProveedor($id)
    {
        $catalogoProveedor = CatalogoProveedor::where('idProveedor', $id)
            ->join('almacen_compras', 'almacen_compras.id', '=', 'catalogo_proveedores.idProducto')
            ->join('proveedores', 'proveedores.id', '=', 'catalogo_proveedores.idProveedor')
            ->select(
                'catalogo_proveedores.*',
                'almacen_compras.sku',
                'almacen_compras.nombre AS nombreProducto',
                'proveedores.nombre AS nombreProveedor'
            )
            ->where('almacen_compras.eliminado', false)
            ->orderBy('nombreProducto', 'asc')
            ->get();

        if (!$catalogoProveedor) {
            return response()->json(Respuestas::respuesta404('proveedor no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('Catálogo de proveedor encontrado.', $catalogoProveedor));
    }

    public function consultarCatalogos()
    {
        $catalogos = CatalogoProveedor::all();

        return response()->json(Respuestas::respuesta200('catalogos encontrados.', $catalogos));
    }

    public function eliminarProductoCatalogo($id)
    {
        if (!$id) {
            return response()->json(Respuestas::respuesta404('id de producto no enviado.'));
        }

        $producto->delete();

        return response()->json(Respuestas::respuesta200NoResultados('Producto eliminado del catálogo.'));
    }
}