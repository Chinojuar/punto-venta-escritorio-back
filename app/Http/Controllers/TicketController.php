<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket;
use App\Models\ProductoTicket;
use App\Models\PagoEfectivo;
use App\Models\PagoVales;
use App\Models\PagoTransferencia;
use App\Models\PagoTarjeta;
use App\Models\CatalogoProductos;
use Illuminate\Support\Collection;


class TicketController extends Controller
{
    public function agregarTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idMetodoPago' => 'required',
            'idUsuario' => 'required',
            'cantidadArticulos' => 'required',
            'iva' => 'required',
            'total' => 'required',
            'productosVenta' => 'required',
            'infoMetodoPago' => 'required',
            'idSucursal' => 'nullable',
            'idCliente' => 'nullable',
            'observaciones' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        if (!isset($request->productosVenta) || !is_array($request->productosVenta)) {
            return response()->json(
                Respuestas::respuesta400('No se encontró productosVenta o no es un array.'),
                400
            );
        }

        $datosTicket = [
            'idMetodoPago' => $request->idMetodoPago,
            'idUsuario' => $request->idUsuario,
            'idSucursal' => $request->idSucursal,
            'idCliente' => $request->idCliente,
            'cantidadArticulos' => $request->cantidadArticulos,
            'iva' => $request->iva,
            'total' => $request->total,
        ];

        $datosTicket = array_filter($datosTicket);

        // Guarda información del ticket
        $ticket = Ticket::create($datosTicket);

        // Guarda los productos del ticket
        foreach ($request->productosVenta as $producto) {
            $productoGuardar = [
                'idProducto' => $producto['id'],
                'idTicket' => $ticket['id'],
                'idMetodoPago' => $ticket['idMetodoPago'],
                'precioVenta' => $producto['precioVenta'],
                'descuento' => $producto['descuento'],
                'observaciones' => $producto['observaciones'],
                'cantidad' => $producto['cantidad'],
            ];

            ProductoTicket::create($productoGuardar);
        }

        // Guarda el método de pago
        if ($request->idMetodoPago == 5) {
            foreach ($request->infoMetodoPago as $infoMetodoPago) {
                $this->guardarMetodoPagoMixto($infoMetodoPago, $ticket, $request->observaciones);
            }
        } else {
            $this->guardarMetodoPago($request, $ticket);
        }



        return response()->json(
            Respuestas::respuesta200NoResultados('Se guardo el ticket.'),
            201
        );
    }

    public function guardarMetodoPago($request, $ticket)
    {
        switch ($request->idMetodoPago) {
            case 1:
                $metodo = [
                    'idTicket' => $ticket->id,
                    'montoEfectivo' => $request->infoMetodoPago['montoEfectivo'],
                    'cambioDevuelto' => $request->infoMetodoPago['cambioDevuelto'],
                    'observacionesEfectivo' => $request->infoMetodoPago['observacionesEfectivo'],
                ];
                PagoEfectivo::create($metodo);
                break;
            case 2:
                foreach ($request->infoMetodoPago as $metodoPago) {
                    $metodo = [
                        'idTicket' => $ticket->id,
                        'codigoVale' => $metodoPago['codigoVale'],
                        'montoVale' => $metodoPago['montoVale'],
                        'observacionesVale' => $metodoPago['observacionesVale'],
                    ];
                    PagoVales::create($metodo);
                }
                break;
            case 3:
                $metodo = [
                    'idTicket' => $ticket->id,
                    'idBanco' => $request->infoMetodoPago['idBanco'],
                    'montoTransferencia' => $request->infoMetodoPago['montoTransferencia'],
                    'observacionesTransferencia' => $request->infoMetodoPago['observacionesTransferencia'],
                ];
                PagoTransferencia::create($metodo);
                break;
            case 4:
                foreach ($request->infoMetodoPago as $tarjeta) {
                    $metodo = [
                        'idTicket' => $ticket->id,
                        'idBanco' => $tarjeta['idBanco'],
                        'tipoTarjeta' => $tarjeta['tipoTarjeta'],
                        'montoTarjeta' => $tarjeta['montoTarjeta'],
                        'cuatroDigitos' => $tarjeta['cuatroDigitos'],
                        'observacionesTarjeta' => $tarjeta['observacionesTarjeta'],
                    ];
                    PagoTarjeta::create($metodo);
                }
                break;
        }
    }


    public function guardarMetodoPagoMixto($infoMetodoPago, $ticket, $observacionMixto)
    {
        switch ($infoMetodoPago['idMetodoPago']) {
            case 1:
                $metodo = [
                    'idTicket' => $ticket->id,
                    'montoEfectivo' => $infoMetodoPago['montoMixto'],
                    'observacionesEfectivo' => $observacionMixto,
                    'mixto' => true
                ];
                PagoEfectivo::create($metodo);
                break;
            case 2:
                $metodo = [
                    'idTicket' => $ticket->id,
                    'codigoVale' => $infoMetodoPago['codigoVale'],
                    'montoVale' => $infoMetodoPago['montoMixto'],
                    'observacionesVale' => $observacionMixto,
                    'mixto' => true
                ];
                PagoVales::create($metodo);
                break;
            case 3:
                $metodo = [
                    'idTicket' => $ticket->id,
                    'idBanco' => $infoMetodoPago['idBancoTransferencia'],
                    'montoTransferencia' => $infoMetodoPago['montoMixto'],
                    'observacionesTransferencia' => $observacionMixto,
                    'mixto' => true
                ];
                PagoTransferencia::create($metodo);
                break;
            case 4:
                $metodo = [
                    'idTicket' => $ticket->id,
                    'idBanco' => $infoMetodoPago['idBancoTarjeta'],
                    'tipoTarjeta' => $infoMetodoPago['tipoTarjeta'],
                    'montoTarjeta' => $infoMetodoPago['montoMixto'],
                    'cuatroDigitos' => $infoMetodoPago['cuatroDigitos'],
                    'observacionesTarjeta' => $observacionMixto,
                    'mixto' => true
                ];
                PagoTarjeta::create($metodo);
                break;
        }
    }

    private function mockPagoMixto($ticket, $fecha_actual)
    {
        $totalMixto = $ticket['total'];
        $tipos_posibles = ['credito', 'debito'];
        $indicePago = rand(0, 2);
        $dineroPosibleMixto = [
            $totalMixto * 0.5,
            $totalMixto * 0.7,
            $totalMixto * 0.6,
        ];

        $pagos = [$dineroPosibleMixto[$indicePago], $totalMixto - $dineroPosibleMixto[$indicePago]];

        for ($k = 0; $k < 2; $k++) {
            $indiceMetodoPago = rand(1, 4);
            $indiceTipos = rand(0, 1);

            switch ($indiceMetodoPago) {
                case 1:
                    // Crear registro de pago en efectivo
                    $metodo = [
                        'idTicket' => $ticket['id'],
                        'montoEfectivo' => round($pagos[$k],2),
                        'cambioDevuelto' => 0,
                        'observacionesEfectivo' => '',
                        'mixto' => true,
                        'created_at' => $fecha_actual,
                        'updated_at' => $fecha_actual,
                    ];
                    PagoEfectivo::create($metodo);
                    break;
                case 2:
                    // Crear registro de pago en vales
                    $metodo = [
                        'idTicket' => $ticket['id'],
                        'codigoVale' => rand(1127845215, 1875246985),
                        'montoVale' => round($pagos[$k],2),
                        'observacionesVale' => '',
                        'mixto' => true,
                        'created_at' => $fecha_actual,
                        'updated_at' => $fecha_actual,
                    ];
                    PagoVales::create($metodo);
                    break;
                case 3:
                    // Crear registro de pago en transferencia
                    $metodo = [
                        'idTicket' => $ticket['id'],
                        'idBanco' => rand(1, 28),
                        'montoTransferencia' => round($pagos[$k],2),
                        'observacionesTransferencia' => '',
                        'mixto' => true,
                        'created_at' => $fecha_actual,
                        'updated_at' => $fecha_actual,
                    ];
                    PagoTransferencia::create($metodo);
                    break;
                case 4:
                    // Crear registro de pago en tarjeta
                    $metodo = [
                        'idTicket' => $ticket['id'],
                        'idBanco' => rand(1, 28),
                        'tipoTarjeta' => $tipos_posibles[$indiceTipos],
                        'montoTarjeta' => round($pagos[$k],2),
                        'cuatroDigitos' => rand(1245, 7856),
                        'observacionesTarjeta' => '',
                        'mixto' => true,
                        'created_at' => $fecha_actual,
                        'updated_at' => $fecha_actual,
                    ];
                    PagoTarjeta::create($metodo);
                    break;
            }
        }
    }



    private function datosMockMetodoPago($idMetodoPago, $ticket, $fecha_actual)
    {
        switch ($idMetodoPago) {
            case 1:
                $cambio = rand(20, 200);
                $montoEfectivo = round($ticket['total'] + $cambio, 2);
                if ($montoEfectivo > 0) {
                    $metodo = [
                        'idTicket' => $ticket['id'],
                        'montoEfectivo' => $montoEfectivo,
                        'cambioDevuelto' => $cambio,
                        'observacionesEfectivo' => '',
                        'created_at' => $fecha_actual,
                        'updated_at' => $fecha_actual,
                    ];
                    PagoEfectivo::create($metodo);
                }
                break;
            case 2:
                $vales = [];
                $totalMenosVales = $ticket['total'];
                $valores_posibles = array(50, 100, 200, 500);
                while ($totalMenosVales > 0) {
                    $indice_aleatorio = rand(0, 3);
                    $montoVale = min($totalMenosVales, $valores_posibles[$indice_aleatorio]);
                    if ($montoVale > 0) {
                        $totalMenosVales -= $montoVale;

                        $dato = [
                            'idTicket' => $ticket['id'],
                            'codigoVale' => rand(1127845215, 1875246985),
                            'montoVale' => round($montoVale, 2),
                            'observacionesVale' => '',
                            'created_at' => $fecha_actual,
                            'updated_at' => $fecha_actual,
                        ];

                        array_push($vales, $dato);
                        PagoVales::create($dato);
                    }
                }
                break;
            case 3:
                $montoTransferencia = round($ticket['total'], 2);
                if ($montoTransferencia > 0) {
                    $metodo = [
                        'idTicket' => $ticket['id'],
                        'idBanco' => rand(1, 28),
                        'montoTransferencia' => $montoTransferencia,
                        'observacionesTransferencia' => '',
                        'created_at' => $fecha_actual,
                        'updated_at' => $fecha_actual,
                    ];
                    PagoTransferencia::create($metodo);
                }
                break;
            case 4:
                $tarjetas = [];
                $totalTarjeta = $ticket['total'];
                $tipos_posibles = ['credito', 'debito'];
                $dineroPosible = [
                    $totalTarjeta * 0.5,
                    $totalTarjeta * 0.7,
                ];

                while ($totalTarjeta > 0) {
                    $indice_aleatorio = rand(0, 1);
                    $indiceDinero = rand(0, 1);

                    $montoTarjeta = min($totalTarjeta, $dineroPosible[$indiceDinero]);
                    if ($montoTarjeta > 0) {
                        $metodoTarjeta = [
                            'idTicket' => $ticket['id'],
                            'idBanco' => rand(1, 28),
                            'tipoTarjeta' => $tipos_posibles[$indice_aleatorio],
                            'montoTarjeta' => round($montoTarjeta, 2),
                            'cuatroDigitos' => rand(1325, 9875),
                            'observacionesTarjeta' => '',
                            'created_at' => $fecha_actual,
                            'updated_at' => $fecha_actual,
                        ];

                        array_push($tarjetas, $metodoTarjeta);
                        PagoTarjeta::create($metodoTarjeta);

                        $totalTarjeta -= $montoTarjeta;
                    }
                }
                break;
        }
    }




    public function base()
    {
        set_time_limit(360);
        $fecha_actual = new \DateTime('2023-01-01');
        $fecha_final = new \DateTime('2023-12-31');
        $respuesta = [];
        $productos = CatalogoProductos::where('id', '!=', 1)->get();
        $registro = 0;


        while ($fecha_actual <= $fecha_final) {
            $registro = rand(10, 30);
            $dia = $fecha_actual->format('d');
            $mes = $fecha_actual->format('m');
            $anio = $fecha_actual->format('Y');

            $fecha_hora = new \DateTime("$anio-$mes-$dia");


            for ($i = 1; $i <= $registro; $i++) {
                $hora = rand(9, 20);
                $minutos = rand(0, 60);
                $segundos = rand(0, 60);
                $fecha_hora->setTime($hora, $minutos, $segundos);

                $idMetodoPago = rand(1, 5);
                $cantidadArticulos = rand(1, 10);
                $totalTicket = 0;
                $cantidadTotalArticulos = 0;
                $productosVenta = [];

                for ($j = 1; $j <= $cantidadArticulos; $j++) {
                    $productoGuardar = $productos[rand(0, 66)];
                    $productoGuardar['cantidad'] = 1;
                    $cantidadTotalArticulos += 1;
                    $totalTicket = $totalTicket + $productoGuardar['precioVenta'];
                    array_push($productosVenta, $productoGuardar);
                }

                $coleccionProductosVenta = new Collection($productosVenta);

                // Agrupar los productos por su ID y luego sumar las cantidades
                $productosVentaNuevo = $coleccionProductosVenta->groupBy('id')
                    ->map(function ($grupoProductos) {
                        // El grupo contiene todos los productos con el mismo ID
                        // Aquí sumamos las cantidades y devolvemos el primer producto como referencia
                        $cantidad = $grupoProductos->sum('cantidad');
                        $primerProducto = $grupoProductos->first();
                        $primerProducto['cantidad'] = $cantidad;
                        return $primerProducto;
                    })
                    ->values() // Reindexar el array resultante
                    ->toArray();

                $infoTicket = [
                    'idMetodoPago' => $idMetodoPago,
                    'idUsuario' => 1,
                    'cantidadArticulos' => $cantidadTotalArticulos,
                    'iva' => 0.16,
                    'total' => round($totalTicket, 2),
                    'created_at' => $fecha_hora->format('Y-m-d H:i:s'),
                    'updated_at' => $fecha_hora->format('Y-m-d H:i:s'),
                ];

                $ticketGuardado = Ticket::create($infoTicket);
                //$infoTicket['id'] = 1;

                if ($idMetodoPago == 5) {
                    $this->mockPagoMixto($ticketGuardado, $fecha_actual);
                } else {
                    $this->datosMockMetodoPago($idMetodoPago, $ticketGuardado, $fecha_actual);
                }
                foreach ($productosVenta as $producto) {
                    $productoGuardar = [
                        'idProducto' => $producto['id'],
                        'idTicket' => $ticketGuardado->id,
                        'idMetodoPago' => $infoTicket['idMetodoPago'],
                        'precioVenta' => $producto['precioVenta'],
                        'descuento' => $producto['descuento'],
                        'observaciones' => $producto['observaciones'],
                        'cantidad' => $producto['cantidad'],
                    ];
                    ProductoTicket::create($productoGuardar);
                }

                $infoTicket['productosVenta'] = $productosVentaNuevo;

                array_push($respuesta, $infoTicket);
            }

            $fecha_actual->modify('+1 day');
        }

        return response()->json(
            Respuestas::respuesta200('Se guardo el ticket.', $respuesta),
            201
        );
    }
}