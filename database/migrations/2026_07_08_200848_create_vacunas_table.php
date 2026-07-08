<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacunas', function (Blueprint $table) {
            $table->id();

            // Animal al que se aplicó la vacuna o tratamiento
            $table->foreignId('animal_id')->constrained('animales')->cascadeOnDelete();

            $table->string('tipo', 150);
            $table->date('fecha_aplicada');

            // Fecha programada para la próxima dosis
            $table->date('proxima_fecha')->nullable();

            $table->string('aplicada_por', 100)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacunas');
    }
};