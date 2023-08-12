<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductoOrdenCompra;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use App\Models\CatalogoProductos;

class ProductoOrdenCompraController extends Controller
{
    public function crearProductoOrdenCompra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idOrdenCompra' => 'required',
            'idProveedor' => 'required',
            'idProducto' => 'required',
            'precioCompra' => 'required',
            'cantidadCompra' => 'required',
            'descuento' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $producto = CatalogoProductos::where('id', $request->idProducto)
            ->get()[0];
        $datosActualizado = [
            'cantidadStock' => $producto->cantidadStock +
                $request->cantidadCompra,
        ];
        $datosActualizado = array_filter($datosActualizado);
        CatalogoProductos::where('id', $request->input('idProducto'))
         ->update($datosActualizado);

        $productoOrdenCompra = ProductoOrdenCompra::create($request->all());


        return response()->json(Respuestas::respuesta200('Orden de compra creada.', $productoOrdenCompra), 201);
    }
}
