<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VacunaController extends Controller
{
    // Retorna todas las vacunas registradas
    public function index()
    {
        $vacunas = Vacuna::with('animal')
            ->orderBy('proxima_fecha', 'asc')
            ->get();

        return response()->json($vacunas, 200);
    }

    // Retorna vacunas próximas a vencer en los próximos 30 días
    public function proximas()
    {
        $hoy   = Carbon::today();
        $limite = Carbon::today()->addDays(30);

        $vacunas = Vacuna::with('animal')
            ->whereBetween('proxima_fecha', [$hoy, $limite])
            ->orderBy('proxima_fecha', 'asc')
            ->get();

        return response()->json($vacunas, 200);
    }

    // Retorna vacunas cuya fecha próxima ya venció
    public function vencidas()
    {
        $vacunas = Vacuna::with('animal')
            ->where('proxima_fecha', '<', Carbon::today())
            ->orderBy('proxima_fecha', 'asc')
            ->get();

        return response()->json($vacunas, 200);
    }

    // Registra una nueva vacuna o tratamiento
    public function store(Request $request)
    {
        $request->validate([
            'animal_id'      => 'required|exists:animales,id',
            'tipo'           => 'required|string|max:150',
            'fecha_aplicada' => 'required|date',
            'proxima_fecha'  => 'nullable|date|after:fecha_aplicada',
            'aplicada_por'   => 'nullable|string|max:100',
            'observaciones'  => 'nullable|string',
        ]);

        $vacuna = Vacuna::create($request->all());

        return response()->json($vacuna->load('animal'), 201);
    }

    // Actualiza un registro de vacuna
    public function update(Request $request, int $id)
    {
        $vacuna = Vacuna::find($id);

        if (!$vacuna) {
            return response()->json(['message' => 'Vacuna no encontrada.'], 404);
        }

        $request->validate([
            'tipo'           => 'sometimes|string|max:150',
            'fecha_aplicada' => 'sometimes|date',
            'proxima_fecha'  => 'nullable|date',
            'aplicada_por'   => 'nullable|string|max:100',
            'observaciones'  => 'nullable|string',
        ]);

        $vacuna->update($request->all());

        return response()->json($vacuna->load('animal'), 200);
    }

    // Elimina un registro de vacuna
    public function destroy(int $id)
    {
        $vacuna = Vacuna::find($id);

        if (!$vacuna) {
            return response()->json(['message' => 'Vacuna no encontrada.'], 404);
        }

        $vacuna->delete();

        return response()->json(['message' => 'Vacuna eliminada correctamente.'], 200);
    }
}