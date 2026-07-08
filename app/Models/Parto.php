<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parto extends Model
{
    protected $table = 'partos';
    protected $fillable = [
        'madre_id',
        'fecha_parto',
        'resultado',
        'cria_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_parto' => 'date',
    ];

    // Relación: el parto pertenece a una vaca madre
    public function madre()
    {
        return $this->belongsTo(Animal::class, 'madre_id');
    }

    // Relación: el parto puede tener una cría registrada
    public function cria()
    {
        return $this->belongsTo(Animal::class, 'cria_id');
    }
}