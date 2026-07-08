<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reproduccion', function (Blueprint $table) {
            $table->id();

            // Vaca a la que pertenece el registro reproductivo
            $table->foreignId('animal_id')->constrained('animales')->cascadeOnDelete();

            $table->date('fecha_celo');
            $table->boolean('esta_prenada')->default(false);

            // Se calcula automáticamente: fecha_celo + 283 días
            $table->date('fecha_probable_parto')->nullable();

            // Toro utilizado en el cruce
            $table->foreignId('toro_id')->nullable()->constrained('animales')->nullOnDelete();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reproduccion');
    }
};