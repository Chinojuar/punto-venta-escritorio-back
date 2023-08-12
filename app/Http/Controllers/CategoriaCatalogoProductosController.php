<?php

namespace App\Http\Controllers;

use App\Models\CategoriaCatalogoProductos;
use App\Models\CatalogoProductos;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaCatalogoProductosController extends Controller
{
    public function consultarCategoriasVentas()
    {
        $categorias = CategoriaCatalogoProductos::all();
        return response()->json(Respuestas::respuesta200('Categorías encontradas.', $categorias));
    }

    public function crearCategoriaCatalogoProductoss(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        CategoriaCatalogoProductos::create($request->all());

        $categorias = CategoriaCatalogoProductos::all();

        return response()->json(Respuestas::respuesta200('Categoria creado.', $categorias), 201);
    }

    public function eliminarCategoriaCatalogoProductoss($id)
    {
        $categoria = CategoriaCatalogoProductos::find($id);

        CatalogoProductos::where('idCategoria', $id)->update(['idCategoria' => 1]);

        if (!$categoria) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'), 404);
        }

        $categoria->delete();
        $categorias = CategoriaCatalogoProductos::all();
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

        $respuesta = [
            'categorias' => $categorias,
            'productos' => $productos,
        ];

        return response()->json(Respuestas::respuesta200('Categoria eliminado', $respuesta));
    }
}
