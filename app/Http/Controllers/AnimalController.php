<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    // Retorna todos los animales del hato
    public function index()
    {
        $animales = Animal::orderBy('numero_identificacion')->get();

        return response()->json($animales, 200);
    }

    // Retorna el detalle de un animal con sus relaciones
    public function show(int $id)
    {
        $animal = Animal::with(['madre', 'padre', 'partos', 'vacunas', 'reproducciones'])
            ->find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal no encontrado.'], 404);
        }

        return response()->json($animal, 200);
    }

    // Registra un nuevo animal en el hato
    public function store(Request $request)
    {
        $request->validate([
            'numero_identificacion' => 'required|string|max:50|unique:animales',
            'nombre'                => 'nullable|string|max:100',
            'fecha_nacimiento'      => 'nullable|date',
            'sexo'                  => 'required|in:vaca,toro,ternero,ternera',
            'raza'                  => 'nullable|string|max:100',
            'estado'                => 'required|in:activa,vendida,muerta',
            'foto_url'              => 'nullable|string|max:500',
            'madre_id'              => 'nullable|exists:animales,id',
            'padre_id'              => 'nullable|exists:animales,id',
        ]);

        $animal = Animal::create($request->all());

        return response()->json($animal, 201);
    }

    // Actualiza los datos de un animal
    public function update(Request $request, int $id)
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal no encontrado.'], 404);
        }

        $request->validate([
            'numero_identificacion' => 'sometimes|string|max:50|unique:animales,numero_identificacion,' . $id,
            'nombre'                => 'nullable|string|max:100',
            'fecha_nacimiento'      => 'nullable|date',
            'sexo'                  => 'sometimes|in:vaca,toro,ternero,ternera',
            'raza'                  => 'nullable|string|max:100',
            'estado'                => 'sometimes|in:activa,vendida,muerta',
            'foto_url'              => 'nullable|string|max:500',
            'madre_id'              => 'nullable|exists:animales,id',
            'padre_id'              => 'nullable|exists:animales,id',
        ]);

        $animal->update($request->all());

        return response()->json($animal, 200);
    }

    // Elimina un animal del hato
    public function destroy(int $id)
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal no encontrado.'], 404);
        }

        $animal->delete();

        return response()->json(['message' => 'Animal eliminado correctamente.'], 200);
    }
}