<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AlmacenComprasController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaCatalogoProductosController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CatalogoProveedorController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ProductoOrdenCompraController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\CatalogoProductosController;
use App\Http\Controllers\CatalogoBancoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CarpetasController;
use App\Http\Controllers\AnalisisController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group([
    'prefix' => 'documentos'
], function ($router) {
    Route::post('guardar-documento', [DocumentosController::class, 'guardarArchivo']);
    Route::put('actualizar-nombre', [DocumentosController::class, 'actualizarInfoDocumento']);
    Route::post('traer-archivo', [DocumentosController::class, 'traerArchivo']);
    Route::post('descargar-archivo', [DocumentosController::class, 'descargarArchivo']);
    Route::get('traer-documentos', [DocumentosController::class, 'traerTodosDocumentos']);
    Route::get('traer-documentos-area/{area}', [DocumentosController::class, 'traerDocumentosArea']);
    Route::post('actualizar-documento', [DocumentosController::class, 'actualizarDocumento']);
    Route::delete('borrar-documento/{id}', [DocumentosController::class, 'borrarDocumento']);
    Route::get('descargar-documento/{uuid}/{extension}', [DocumentosController::class, 'descargarDocumento']);
});

Route::group([
    'prefix' => 'perfil'
], function ($router) {
    Route::post('confirmar-contrasena', [PerfilController::class, 'verificarContrasena']);
    Route::post('guardar-informacion', [PerfilController::class, 'guardarInformacionUsuario']);
    Route::post('actualizar-contrasena', [PerfilController::class, 'actualizarContrasena']);
    Route::post('guardar-imagen', [PerfilController::class, 'guardarImagen']);
});

Route::group([
    'prefix' => 'producto'
], function ($router) {
    Route::post('guardar-producto', [AlmacenComprasController::class, 'crearProducto']);
    Route::put('actualizar-producto', [AlmacenComprasController::class, 'actualizarProducto']);
    Route::get('consultar-producto/{id}', [AlmacenComprasController::class, 'consultarProducto']);
    Route::get('consultar-productos', [AlmacenComprasController::class, 'consultarProductos']);
    Route::get('consultar-productos-venta', [AlmacenComprasController::class, 'consultarProductosVenta']);
    Route::delete('eliminar-producto/{id}', [AlmacenComprasController::class, 'eliminarProducto']);
});

Route::group([
    'prefix' => 'categorias'
], function ($router) {
    Route::get('consultar-categorias', [CategoriaController::class, 'consultarCategorias']);
    Route::post('guardar-categoria', [CategoriaController::class, 'crearCategoria']);
    Route::delete('eliminar-categoria/{id}', [CategoriaController::class, 'eliminarCategoria']);
});

Route::group([
    'prefix' => 'categorias-ventas'
], function ($router) {
    Route::get('consultar-categorias', [CategoriaCatalogoProductosController::class, 'consultarCategoriasVentas']);
    Route::post('guardar-categoria', [CategoriaCatalogoProductosController::class, 'crearCategoriaCatalogoProductoss']);
    Route::delete('eliminar-categoria/{id}', [CategoriaCatalogoProductosController::class, 'eliminarCategoriaCatalogoProductoss']);
});

Route::group([
    'prefix' => 'empleados'
], function ($router) {
    Route::post('guardar-empleado', [EmpleadoController::class, 'crearEmpleado']);
    Route::put('actualizar-empleado', [EmpleadoController::class, 'actualizarEmpleado']);
    Route::delete('eliminar-empleado/{id}', [EmpleadoController::class, 'eliminarEmpleado']);
    Route::get('consultar-empleado/{id}', [EmpleadoController::class, 'consultarEmpleado']);
    Route::get('consultar-empleados', [EmpleadoController::class, 'consultarTodosEmpleados']);
    Route::post('guardar-documento', [EmpleadoController::class, 'guardarArchivo']);
    Route::post('traer-archivo', [EmpleadoController::class, 'traerArchivo']);
    Route::post('descargar-archivo', [EmpleadoController::class, 'descargarArchivo']);
    Route::get('traer-documentos', [EmpleadoController::class, 'traerTodosDocumentos']);
    Route::get('traer-documentos-area/{area}', [EmpleadoController::class, 'traerDocumentosArea']);
    Route::post('actualizar-documento', [EmpleadoController::class, 'actualizarDocumento']);
    Route::delete('borrar-documento', [EmpleadoController::class, 'borrarDocumento']);
    Route::get('descargar-documento/{uuid}/{extension}/{area}/{nombre_archivo}', [EmpleadoController::class, 'descargarDocumento']);
    Route::post('guardar-asistencia', [EmpleadoController::class, 'guardarAsistencia']);
    Route::post('actualizar-asistencia', [EmpleadoController::class, 'actualizarAsistencia']);
    Route::get('traer-registro/{id_emp}/{dia}/{mes}/{anio}', [EmpleadoController::class, 'traerRegistroAsistencia']);
});

Route::group([
    'prefix' => 'proveedores'
], function ($router) {
    Route::post('guardar-proveedor', [ProveedorController::class, 'crearProveedor']);
    Route::put('actualizar-proveedor', [ProveedorController::class, 'actualizarProveedor']);
    Route::delete('eliminar-proveedor/{id}', [ProveedorController::class, 'eliminarProveedor']);
    Route::get('consultar-proveedor/{id}', [ProveedorController::class, 'consultarProveedor']);
    Route::get('consultar-proveedores', [ProveedorController::class, 'consultarTodosProveedores']);
});

Route::group([
    'prefix' => 'catalogo-proveedores'
], function ($router) {
    Route::post('guardar-catalogo', [CatalogoProveedorController::class, 'crearProductoCatalogo']);
    Route::put('actualizar-catalogo', [CatalogoProveedorController::class, 'actualizarProductoCatalogo']);
    Route::delete('eliminar-catalogo/{id}', [CatalogoProveedorController::class, 'eliminarProductoCatalogo']);
    Route::get('consultar-catalogo/{id}', [CatalogoProveedorController::class, 'consultarCatalogoProveedor']);
    Route::get('consultar-catalogos', [CatalogoProveedorController::class, 'consultarCatalogos']);
});

Route::group([
    'prefix' => 'areas'
], function ($router) {
    Route::get('consultar-areas', [AreasController::class, 'consultarAreas']);
});

Route::group([
    'prefix' => 'ordenes-compra'
], function ($router) {
    Route::post('guardar-orden-compra', [OrdenCompraController::class, 'crearOrdenCompra']);
    Route::get('consultar-orden-compra', [OrdenCompraController::class, 'consultarOrdenesCompra']);
});

Route::group([
    'prefix' => 'productos-ordenes-compra'
], function ($router) {
    Route::post('guardar-producto-orden-compra', [ProductoOrdenCompraController::class, 'crearProductoOrdenCompra']);
});

Route::group([
    'prefix' => 'calendario'
], function ($router) {
    Route::get('consultar-calendario-usuario/{ano}/{idUsuario}', [CalendarioController::class, 'consultarCalendarioUsuario']);
    Route::delete('eliminar-evento-calendario/{id}', [CalendarioController::class, 'eliminarEventoCalendarioUsuario']);
    Route::post('crear-evento-calendario', [CalendarioController::class, 'crearEventoCalendario']);
});

Route::group([
    'prefix' => 'sucursales'
], function ($router) {
    Route::get('consultar-sucursales', [SucursalesController::class, 'consultarSucursales']);
    Route::delete('eliminar-sucursal/{id}', [SucursalesController::class, 'eliminarSucursal']);
    Route::post('crear-sucursal', [SucursalesController::class, 'crearSucursal']);
    Route::put('actualizar-sucursal', [SucursalesController::class, 'actualizarSucursal']);
});

Route::group([
    'prefix' => 'stock-ventas'
], function ($router) {
    Route::get('consultar-productos', [CatalogoProductosController::class, 'consultarProductosVentas']);
    Route::get('consultar-productos-filtrados', [CatalogoProductosController::class, 'consultarProductosVentasFiltrado']);
    Route::post('guardar-producto', [CatalogoProductosController::class, 'crearProductoVentas']);
    Route::put('actualizar-producto', [CatalogoProductosController::class, 'actualizarProductoVentas']);
    Route::delete('eliminar-producto/{id}', [CatalogoProductosController::class, 'eliminarProductoVentas']);
});

Route::group([
    'prefix' => 'catalogo-bancos'
], function ($router) {
    Route::get('consultar', [CatalogoBancoController::class, 'consultarCatalogo']);
});

Route::group([
    'prefix' => 'tickets'
], function ($router) {
    Route::post('guardar', [TicketController::class, 'agregarTicket']);
    Route::get('base', [TicketController::class, 'base']);
});

Route::group([
    'prefix' => 'carpetas'
], function ($router) {
    Route::post('guardar', [CarpetasController::class, 'guardarCarpeta']);
    Route::put('actualizar', [CarpetasController::class, 'actualizarCarpeta']);
    Route::get('consultar', [CarpetasController::class, 'consultarCarpetasDocumentos']);
    Route::delete('eliminar/{id}', [CarpetasController::class, 'eliminarCarpeta']);
});


Route::group([
    'prefix' => 'analisis'
], function ($router) {
    Route::get('ventas-mes', [AnalisisController::class, 'ventasTotalesMes']);
    Route::get('informacion-ventas', [AnalisisController::class, 'consultaInformacionVentas']);
    Route::get('ventas-diarias-mes/{mes}', [AnalisisController::class, 'ventasPorDiaUnMes']);
});


