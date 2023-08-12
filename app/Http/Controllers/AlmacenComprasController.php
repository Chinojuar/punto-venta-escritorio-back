<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\AlmacenCompras;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlmacenComprasController extends Controller
{
    public function consultarProductos()
    {
        $productos = AlmacenCompras::where('eliminado', false)
            ->join('categorias_almacen_compras', 'almacen_compras.categoria', '=', 'categorias_almacen_compras.id')
            ->select('almacen_compras.*', 'categorias_almacen_compras.categoria', 'categorias_almacen_compras.id AS idCategoria')
            ->orderBy('almacen_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Productos encontrados.', $productos));
    }

    public function crearProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'codigoBarras' => 'required',
            'categoria' => 'required',
            'cantidadStock' => 'required',
            'sku' => 'required',
            'stockCompras' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        AlmacenCompras::create($request->all());
        $producto = AlmacenCompras::where('eliminado', false)
            ->join('categorias_almacen_compras', 'almacen_compras.categoria', '=', 'categorias_almacen_compras.id')
            ->select('almacen_compras.*', 'categorias_almacen_compras.categoria', 'categorias_almacen_compras.id AS idCategoria')
            ->orderBy('almacen_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto creado.', $producto), 201);
    }

    public function consultarProducto($id)
    {
        $producto = AlmacenCompras::find($id);

        if (!$producto) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('Producto encontrado.', $producto));
    }

    public function actualizarProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'nullable',
            'descripcion' => 'nullable',
            'codigoBarras' => 'nullable',
            'categoria' => 'nullable',
            'cantidadStock' => 'nullable',
            'comprasTotales' => 'nullable',
            'agotado' => 'boolean|nullable',
            'sku' => 'nullable',
            'eliminado' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'codigoBarras' => $request->codigoBarras,
            'cantidadStock' => $request->cantidadStock,
            'ventas' => $request->ventas,
            'sku' => $request->sku,
            'agotado' => $request->agotado,
            'eliminado' => $request->eliminado,
        ];

        $datosActualizado = array_filter($datosActualizado);

        if ($request->agotado != 1) {
            $datosActualizado['agotado'] = 0;
        }

        AlmacenCompras::where('id', $request->input('id'))
            ->update($datosActualizado);

        $productos = AlmacenCompras::where('eliminado', false)
            ->join('categorias_almacen_compras', 'almacen_compras.categoria', '=', 'categorias_almacen_compras.id')
            ->select('almacen_compras.*', 'categorias_almacen_compras.categoria', 'categorias_almacen_compras.id AS idCategoria')
            ->orderBy('almacen_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $productos));
    }

    public function eliminarProducto($id)
    {
        if (!$id) {
            return response()->json(Respuestas::respuesta404('id de producto no enviado.'));
        }

        AlmacenCompras::where('id', $id)->update(['eliminado' => true]);

        $productos = AlmacenCompras::where('eliminado', false)
            ->join('categorias_almacen_compras', 'almacen_compras.categoria', '=', 'categorias_almacen_compras.id')
            ->select('almacen_compras.*', 'categorias_almacen_compras.categoria', 'categorias_almacen_compras.id AS idCategoria')
            ->orderBy('almacen_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto eliminado', $productos));
    }

    public function consultarProductosVenta()
    {
        $idCategoria = Categoria::where('categoria', 'Venta')->get()[0]->id;
        $productos = AlmacenCompras::where('almacen_compras.categoria', $idCategoria)
            ->where('almacen_compras.eliminado', false)
            ->join('categorias_almacen_compras', 'almacen_compras.categoria', '=', 'categorias_almacen_compras.id')
            ->select('almacen_compras.*', 'categorias_almacen_compras.categoria', 'categorias_almacen_compras.id AS idCategoria')
            ->orderBy('almacen_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto eliminado', $productos));
    }
}