<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de registro de partos del hato.
     */
    public function up(): void
    {
        Schema::create('partos', function (Blueprint $table) {
            $table->id();

            // Vaca que dio a luz
            $table->foreignId('madre_id')->constrained('animales')->cascadeOnDelete();

            // Datos del parto
            $table->date('fecha_parto');
            $table->enum('resultado', ['vivo', 'muerto']);

            // Si la cría quedó en el hato se registra como animal nuevo
            $table->foreignId('cria_id')->nullable()->constrained('animales')->nullOnDelete();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('partos');
    }
};