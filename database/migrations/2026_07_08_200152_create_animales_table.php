<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla principal de animales del hato ganadero.
     */
    public function up(): void
    {
        Schema::create('animales', function (Blueprint $table) {
            $table->id();

            // Identificación del animal
            $table->string('numero_identificacion', 50)->unique();
            $table->string('nombre', 100)->nullable();

            // Datos biológicos
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['vaca', 'toro', 'ternero', 'ternera']);
            $table->string('raza', 100)->nullable();

            // Estado actual en el hato
            $table->enum('estado', ['activa', 'vendida', 'muerta'])->default('activa');

            // Foto opcional almacenada como URL
            $table->string('foto_url', 500)->nullable();

            // Trazabilidad familiar
            $table->foreignId('madre_id')->nullable()->constrained('animales')->nullOnDelete();
            $table->foreignId('padre_id')->nullable()->constrained('animales')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animales');
    }
};