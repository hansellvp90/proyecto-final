<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Si el usuario está autenticado, redirigimos a dashboard; si no, mostramos welcome.
Route::get('/', function () {
    // Usamos la fachada Auth::check() en lugar del helper auth()
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Página para gestionar estudiantes (UI que consume la API) — protegida
Route::get('/estudiantes', function () {
    return view('estudiantes.index');
})->middleware(['auth'])->name('estudiantes.index');

require __DIR__.'/auth.php';
