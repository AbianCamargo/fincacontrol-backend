<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Parto;
use Illuminate\Http\Request;

class PartoController extends Controller
{
    // Retorna todos los partos registrados
    public function index()
    {
        $partos = Parto::with(['madre', 'cria'])
            ->orderBy('fecha_parto', 'desc')
            ->get();

        return response()->json($partos, 200);
    }

    // Registra un nuevo parto
    public function store(Request $request)
    {
        $request->validate([
            'madre_id'      => 'required|exists:animales,id',
            'fecha_parto'   => 'required|date',
            'resultado'     => 'required|in:vivo,muerto',
            'observaciones' => 'nullable|string',
        ]);

        $parto = Parto::create($request->only([
            'madre_id',
            'fecha_parto',
            'resultado',
            'observaciones',
        ]));

        // Si la cría nació viva se crea automáticamente como nuevo animal
        if ($request->resultado === 'vivo' && $request->get('queda_en_hato')) {
            $madre = Animal::find($request->madre_id);

            // Cuenta cuántas crías tiene la madre para generar el número
            $totalCrias = Parto::where('madre_id', $madre->id)
                ->whereNotNull('cria_id')
                ->count();

            $numeroCria = $madre->numero_identificacion . '-C' . ($totalCrias + 1);

            $cria = Animal::create([
                'numero_identificacion' => $numeroCria,
                'sexo'                  => $request->get('sexo_cria', 'ternera'),
                'estado'                => 'activa',
                'madre_id'              => $madre->id,
                'fecha_nacimiento'      => $request->fecha_parto,
            ]);

            // Vincula la cría al registro del parto
            $parto->update(['cria_id' => $cria->id]);
        }

        return response()->json($parto->load(['madre', 'cria']), 201);
    }

    // Actualiza un registro de parto
    public function update(Request $request, int $id)
    {
        $parto = Parto::find($id);

        if (!$parto) {
            return response()->json(['message' => 'Parto no encontrado.'], 404);
        }

        $request->validate([
            'fecha_parto'   => 'sometimes|date',
            'resultado'     => 'sometimes|in:vivo,muerto',
            'observaciones' => 'nullable|string',
        ]);

        $parto->update($request->only([
            'fecha_parto',
            'resultado',
            'observaciones',
        ]));

        return response()->json($parto->load(['madre', 'cria']), 200);
    }

    // Elimina un registro de parto
    public function destroy(int $id)
    {
        $parto = Parto::find($id);

        if (!$parto) {
            return response()->json(['message' => 'Parto no encontrado.'], 404);
        }

        $parto->delete();

        return response()->json(['message' => 'Parto eliminado correctamente.'], 200);
    }
}