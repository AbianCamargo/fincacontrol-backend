<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    // Campos que se pueden asignar masivamente
    protected $table = 'animales';
    protected $fillable = [
        'numero_identificacion',
        'nombre',
        'fecha_nacimiento',
        'sexo',
        'raza',
        'estado',
        'foto_url',
        'madre_id',
        'padre_id',
    ];

    // Convierte las fechas automáticamente a objetos Carbon
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Relación: este animal tiene una madre
    public function madre()
    {
        return $this->belongsTo(Animal::class, 'madre_id');
    }

    // Relación: este animal tiene un padre
    public function padre()
    {
        return $this->belongsTo(Animal::class, 'padre_id');
    }

    // Relación: este animal puede tener muchas crías
    public function crias()
    {
        return $this->hasMany(Animal::class, 'madre_id');
    }

    // Relación: este animal tiene muchos partos registrados
    public function partos()
    {
        return $this->hasMany(Parto::class, 'madre_id');
    }

    // Relación: este animal tiene muchas vacunas registradas
    public function vacunas()
    {
        return $this->hasMany(Vacuna::class);
    }

    // Relación: este animal tiene muchos registros reproductivos
    public function reproducciones()
    {
        return $this->hasMany(Reproduccion::class);
    }
}