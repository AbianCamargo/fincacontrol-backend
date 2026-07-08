<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\PartoController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\ReproduccionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Rutas públicas — no requieren autenticación
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas — requieren token válido de Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Animales
    Route::get('/animales',          [AnimalController::class, 'index']);
    Route::get('/animales/{id}',     [AnimalController::class, 'show']);
    Route::post('/animales',         [AnimalController::class, 'store']);
    Route::put('/animales/{id}',     [AnimalController::class, 'update']);
    Route::delete('/animales/{id}',  [AnimalController::class, 'destroy']);

    // Partos
    Route::get('/partos',            [PartoController::class, 'index']);
    Route::post('/partos',           [PartoController::class, 'store']);
    Route::put('/partos/{id}',       [PartoController::class, 'update']);
    Route::delete('/partos/{id}',    [PartoController::class, 'destroy']);

    // Vacunas
    Route::get('/vacunas',           [VacunaController::class, 'index']);
    Route::get('/vacunas/proximas',  [VacunaController::class, 'proximas']);
    Route::get('/vacunas/vencidas',  [VacunaController::class, 'vencidas']);
    Route::post('/vacunas',          [VacunaController::class, 'store']);
    Route::put('/vacunas/{id}',      [VacunaController::class, 'update']);
    Route::delete('/vacunas/{id}',   [VacunaController::class, 'destroy']);

    // Reproducción
    Route::get('/reproduccion',          [ReproduccionController::class, 'index']);
    Route::get('/reproduccion/prenadas', [ReproduccionController::class, 'prenadas']);
    Route::post('/reproduccion',         [ReproduccionController::class, 'store']);
    Route::put('/reproduccion/{id}',     [ReproduccionController::class, 'update']);
    Route::delete('/reproduccion/{id}',  [ReproduccionController::class, 'destroy']);
});