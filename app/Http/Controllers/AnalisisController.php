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

class AnalisisController extends Controller
{
    public function ventasTotalesMes()
    {
        // Agrupar los registros por mes y obtener la suma de los totales para cada mes
        $monthlyTotals = Ticket::selectRaw('SUM(total) as total, DATE_FORMAT(created_at, "%Y-%m") as month')
            ->groupBy('month')
            ->get();

        // Crear un array para almacenar los totales por mes
        $totalsByMonth = [];

        // Recorrer los resultados y almacenar los totales en el array
        foreach ($monthlyTotals as $monthlyTotal) {
            $totalsByMonth[] = round($monthlyTotal->total, 2);
        }

        return response()->json(Respuestas::respuesta200('Ventas totales.', $totalsByMonth));
    }

    public function ventasPorDiaUnMes($mes)
    {

        $monthlyTotals = Ticket::selectRaw('ROUND(SUM(total), 2) as total, DATE(created_at) as day')
            ->whereRaw('MONTH(created_at) = ?', [$mes])
            ->groupBy('day')
            ->get();

        $totalsArray = $monthlyTotals->pluck('total')->toArray();

        return response()->json(Respuestas::respuesta200('Ticket encontrados.', $totalsArray));
    }

    public function consultaInformacionVentas() {
        $totalRegistros = Ticket::count();

        $tickets = Ticket::all();
        $totalProductos = 0;
        $totalEfectivo = 0;

        foreach ($tickets as $ticket) {
            $totalProductos += $ticket['cantidadArticulos'];
            $totalEfectivo += $ticket['total'];
        }

        $respuesta = [
            'totalTicket' =>  $totalRegistros,
            'totalProductos' =>  $totalProductos,
            'totalEfectivo' =>  round($totalEfectivo,2),
        ];

        return response()->json(Respuestas::respuesta200('Información de ventas.', $respuesta));
    }
}