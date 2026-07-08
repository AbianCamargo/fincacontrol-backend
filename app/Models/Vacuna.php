<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $fillable = [
        'animal_id',
        'tipo',
        'fecha_aplicada',
        'proxima_fecha',
        'aplicada_por',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicada' => 'date',
        'proxima_fecha'  => 'date',
    ];

    // Relación: la vacuna pertenece a un animal
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}