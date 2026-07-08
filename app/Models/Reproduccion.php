<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reproduccion extends Model
{
    protected $table = 'reproduccion';
    protected $fillable = [
        'animal_id',
        'fecha_celo',
        'esta_prenada',
        'fecha_probable_parto',
        'toro_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_celo'           => 'date',
        'fecha_probable_parto' => 'date',
        'esta_prenada'         => 'boolean',
    ];

    // Relación: el registro pertenece a una vaca
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Relación: referencia al toro utilizado en el cruce
    public function toro()
    {
        return $this->belongsTo(Animal::class, 'toro_id');
    }
}