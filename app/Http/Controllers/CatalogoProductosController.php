<?php

namespace App\Http\Controllers;

use App\Models\CatalogoProductos;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatalogoProductosController extends Controller
{
    public function consultarProductosVentas()
    {
        $productos = CatalogoProductos::where('eliminado', false)
            ->join(
                'categorias_catalogo_productos',
                'catalogo_productos.idCategoria',
                '=',
                'categorias_catalogo_productos.id'
            )
            ->select('catalogo_productos.*', 'categorias_catalogo_productos.categoria')
            ->orderBy('catalogo_productos.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Productos encontrados.', $productos));
    }

    public function consultarProductosVentasFiltrado()
    {
        $productos = CatalogoProductos::where('eliminado', false)
            ->where('catalogo_productos.id', '<>', 1)
            ->where('agotado', 0)
            ->join(
                'categorias_catalogo_productos',
                'catalogo_productos.idCategoria',
                '=',
                'categorias_catalogo_productos.id'
            )
            ->select('catalogo_productos.*', 'categorias_catalogo_productos.categoria')
            ->orderBy('catalogo_productos.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Productos encontrados.', $productos));
    }

    public function crearProductoVentas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreProducto' => 'required',
            'descripcion' => 'required',
            'codigoBarras' => 'required|unique:catalogo_productos',
            'precioVenta' => 'required',
            'idCategoria' => 'required',
            'sku' => 'nullable',
            'stockCompras' => 'nullable',
            'precioCompras' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        CatalogoProductos::create($request->all());
        $producto = CatalogoProductos::where('eliminado', false)
            ->join(
                'categorias_catalogo_productos',
                'catalogo_productos.idCategoria',
                '=',
                'categorias_catalogo_productos.id'
            )
            ->select('catalogo_productos.*', 'categorias_catalogo_productos.categoria')
            ->orderBy('catalogo_productos.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto creado.', $producto), 201);
    }

    public function actualizarProductoVentas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'nullable',
            'descripcion' => 'nullable',
            'codigoBarras' => 'nullable',
            'idCategoria' => 'nullable',
            'precioVenta' => 'nullable',
            'precioCompra' => 'nullable',
            'descuento' => 'nullable',
            'cantidadStock' => 'nullable',
            'ventas' => 'nullable',
            'agotado' => 'nullable',
            'sku' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombreProducto' => $request->nombreProducto,
            'idCategoria' => $request->idCategoria,
            'descripcion' => $request->descripcion,
            'codigoBarras' => $request->codigoBarras,
            'cantidadStock' => $request->cantidadStock,
            'precioVenta' => $request->precioVenta,
            'precioCompra' => $request->precioCompra,
            'descuento' => $request->descuento,
            'ventasTotales' => $request->ventas,
            'sku' => $request->sku,
            'agotado' => $request->agotado,
        ];

        $datosActualizado = array_filter($datosActualizado);

        if ($request->agotado != 1) {
            $datosActualizado['agotado'] = 0;
        }

        CatalogoProductos::where('id', $request->input('id'))
            ->update($datosActualizado);

        $productos = CatalogoProductos::where('eliminado', false)
            ->join(
                'categorias_catalogo_productos',
                'catalogo_productos.idCategoria',
                '=',
                'categorias_catalogo_productos.id'
            )
            ->select('catalogo_productos.*', 'categorias_catalogo_productos.categoria')
            ->orderBy('catalogo_productos.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $productos));
    }

    public function eliminarProductoVentas($id)
    {
        if (!$id) {
            return response()->json(Respuestas::respuesta404('id de producto no enviado.'));
        }

        CatalogoProductos::where('id', $id)->update(['eliminado' => true]);

        $productos = CatalogoProductos::where('eliminado', false)
            ->join(
                'categorias_catalogo_productos',
                'catalogo_productos.idCategoria',
                '=',
                'categorias_catalogo_productos.id'
            )
            ->select('catalogo_productos.*', 'categorias_catalogo_productos.categoria')
            ->orderBy('catalogo_productos.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto eliminado', $productos));
    }
}
