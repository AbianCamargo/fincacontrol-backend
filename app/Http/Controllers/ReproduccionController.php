<?php

namespace App\Http\Controllers;

use App\Models\Reproduccion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReproduccionController extends Controller
{
    // Retorna todos los registros reproductivos
    public function index()
    {
        $registros = Reproduccion::with(['animal', 'toro'])
            ->orderBy('fecha_celo', 'desc')
            ->get();

        return response()->json($registros, 200);
    }

    // Retorna las vacas preñadas actualmente
    public function prenadas()
    {
        $registros = Reproduccion::with(['animal', 'toro'])
            ->where('esta_prenada', true)
            ->orderBy('fecha_probable_parto', 'asc')
            ->get();

        return response()->json($registros, 200);
    }

    // Registra un nuevo evento reproductivo
    public function store(Request $request)
    {
        $request->validate([
            'animal_id'   => 'required|exists:animales,id',
            'fecha_celo'  => 'required|date',
            'esta_prenada'=> 'required|boolean',
            'toro_id'     => 'nullable|exists:animales,id',
            'observaciones' => 'nullable|string',
        ]);

        $datos = $request->only([
            'animal_id',
            'fecha_celo',
            'esta_prenada',
            'toro_id',
            'observaciones',
        ]);

        // Calcula la fecha probable de parto si está preñada (283 días)
        if ($request->esta_prenada) {
            $datos['fecha_probable_parto'] = Carbon::parse($request->fecha_celo)
                ->addDays(283)
                ->toDateString();
        }

        $registro = Reproduccion::create($datos);

        return response()->json($registro->load(['animal', 'toro']), 201);
    }

    // Actualiza un registro reproductivo
    public function update(Request $request, int $id)
    {
        $registro = Reproduccion::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado.'], 404);
        }

        $request->validate([
            'fecha_celo'    => 'sometimes|date',
            'esta_prenada'  => 'sometimes|boolean',
            'toro_id'       => 'nullable|exists:animales,id',
            'observaciones' => 'nullable|string',
        ]);

        $datos = $request->only([
            'fecha_celo',
            'esta_prenada',
            'toro_id',
            'observaciones',
        ]);

        // Recalcula la fecha probable de parto si cambia el estado o la fecha
        if (isset($datos['esta_prenada']) || isset($datos['fecha_celo'])) {
            $prenada    = $datos['esta_prenada'] ?? $registro->esta_prenada;
            $fechaCelo  = $datos['fecha_celo']   ?? $registro->fecha_celo;

            $datos['fecha_probable_parto'] = $prenada
                ? Carbon::parse($fechaCelo)->addDays(283)->toDateString()
                : null;
        }

        $registro->update($datos);

        return response()->json($registro->load(['animal', 'toro']), 200);
    }

    // Elimina un registro reproductivo
    public function destroy(int $id)
    {
        $registro = Reproduccion::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado.'], 404);
        }

        $registro->delete();

        return response()->json(['message' => 'Registro eliminado correctamente.'], 200);
    }
}