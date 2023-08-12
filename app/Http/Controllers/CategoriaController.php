<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function consultarCategorias()
    {
        $categorias = Categoria::all();
        return response()->json(Respuestas::respuesta200('Categorías encontradas.', $categorias));
    }

    public function crearCategoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        Categoria::create($request->all());
        $categorias = Categoria::all();

        return response()->json(Respuestas::respuesta200('Categoria creado.', $categorias), 201);
    }

    public function eliminarCategoria($id)
    {
        $categoria = Categoria::find($id);

        Producto::where('categoria', $id)->update(['categoria' => 1]);

        if (!$categoria) {
            return response()->json(Respuestas::respuesta404('Categoria no encontrado'));
        }

        $categoria->delete();
        $categorias = Categoria::all();
        $productos = Producto::where('eliminado', false)
            ->join(
                'categorias_almacen_compras',
                'almacen_compras.categoria',
                '=',
                'categorias_almacen_compras.id'
            )
            ->select('almacen_compras.*', 'categorias_almacen_compras.categoria', 'categorias_almacen_compras.id AS idCategoria')
            ->orderBy('almacen_compras.nombre', 'asc')
            ->get();

        $respuesta = [
            'categorias' => $categorias,
            'productos' => $productos,
        ];

        return response()->json(Respuestas::respuesta200('Categoria eliminado', $respuesta));
    }
}
