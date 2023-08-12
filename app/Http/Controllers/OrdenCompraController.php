<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CatalogoProveedor;
use App\Models\OrdenCompra;
use App\Models\ProductoOrdenCompra;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdenCompraController extends Controller
{
    public function crearOrdenCompra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idProveedor' => 'required',
            'representanteVendedor' => 'nullable',
            'telefonoVendedor' => 'required',
            'correoVendedor' => 'required',
            'direccionVendedor' => 'required',
            'representanteComprador' => 'nullable',
            'telefonoComprador' => 'required',
            'correoComprador' => 'required',
            'direccionComprador' => 'required',
            'instruccionEspecial' => 'nullable',
            'subtotal' => 'required',
            'descuento' => 'required',
            'otros' => 'required',
            'iva' => 'required',
            'total' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        $ordenCompra = OrdenCompra::create($request->all());

        return response()->json(Respuestas::respuesta200('Orden de compra creada.', $ordenCompra), 201);
    }

    public function consultarOrdenesCompra()
    {
        $ordenesCompraInfo = OrdenCompra::all();
        $catalogoProveedor = ProductoOrdenCompra::join('catalogo_proveedores', function ($join) {
            $join->on('catalogo_proveedores.idProveedor', '=', 'productos_orden_compra.idProveedor')
                ->on('catalogo_proveedores.idProducto', '=', 'productos_orden_compra.idProducto');
        })
            ->join('almacen_compras', 'almacen_compras.id', '=', 'productos_orden_compra.idProducto')
            ->join('orden_compra_info', 'orden_compra_info.id', '=', 'productos_orden_compra.idOrdenCompra')
            ->select('productos_orden_compra.*', 'catalogo_proveedores.politicasVenta', 'almacen_compras.sku', 'almacen_compras.nombre AS nombreProducto')
            ->get();

        $ordenesCompraInfo = $ordenesCompraInfo->map(function ($ordenCompra) use ($catalogoProveedor) {
            $catalogoProveedor =  $catalogoProveedor->map(function ($catalogo) use ($ordenCompra) {
                if ($ordenCompra->idProveedor == $catalogo->idProveedor && $catalogo->idOrdenCompra == $ordenCompra->id) {
                    return $catalogo;
                }
            });

            $arrayCatalogo = [];
            foreach ($catalogoProveedor as $productoCatalogo) {
                if ($productoCatalogo !== null) {
                    array_push($arrayCatalogo, $productoCatalogo);
                }
            }

            $ordenCompra->catalogoProveedor = $arrayCatalogo;
            return $ordenCompra;
        });

        if (!$ordenesCompraInfo) {
            return response()->json(Respuestas::respuesta404('proveedor no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('Catálogo de proveedor encontrado.', $ordenesCompraInfo));
    }
}
