<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Parto;
use App\Models\Vacuna;
use App\Models\Reproduccion;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Retorna el resumen general del hato para el dashboard
    public function index()
    {
        $hoy    = Carbon::today();
        $limite = Carbon::today()->addDays(30);

        return response()->json([
            // Total de animales activos en el hato
            'total_animales' => Animal::where('estado', 'activa')->count(),

            // Vacas preñadas actualmente
            'total_prenadas' => Reproduccion::where('esta_prenada', true)->count(),

            // Vacunas próximas a vencer en los próximos 30 días
            'vacunas_proximas' => Vacuna::whereBetween('proxima_fecha', [$hoy, $limite])->count(),

            // Vacunas que ya vencieron
            'vacunas_vencidas' => Vacuna::where('proxima_fecha', '<', $hoy)->count(),

            // Partos registrados en el mes actual
            'partos_mes' => Parto::whereMonth('fecha_parto', $hoy->month)
                ->whereYear('fecha_parto', $hoy->year)
                ->count(),

            // Detalle de vacunas próximas y vencidas para mostrar alertas
            'alertas_vacunas' => Vacuna::with('animal')
                ->where('proxima_fecha', '<=', $limite)
                ->orderBy('proxima_fecha', 'asc')
                ->get(),

            // Partos del mes con detalle
            'partos_del_mes' => Parto::with('madre')
                ->whereMonth('fecha_parto', $hoy->month)
                ->whereYear('fecha_parto', $hoy->year)
                ->orderBy('fecha_parto', 'desc')
                ->get(),
        ], 200);
    }
}