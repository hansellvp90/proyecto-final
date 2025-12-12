<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EstudianteController;

// Rutas API públicas para estudiantes (CRUD completo)
// Si quieres protegerlas con autenticación, añade middleware('auth:sanctum') o similar.
Route::apiResource('estudiantes', EstudianteController::class);
