<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirige al calendario si está autenticado, de lo contrario, se redirige al login automáticamente
    return redirect()->route('dashboard');
})->middleware('auth');


// Rutas del CRUD de actividades
Route::get('/calendar', function () {
    return view('calendar');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/calendar/agregar', [App\Http\Controllers\ActivityController::class, 'store'])->middleware(['auth', 'verified'])->name('calendar.agregar');

Route::get('/calendar/mostrar', [App\Http\Controllers\ActivityController::class, 'show'])->middleware(['auth', 'verified'])->name('calendar.mostrar');

Route::post('/calendar/editar/{id}', [App\Http\Controllers\ActivityController::class, 'edit'])->middleware(['auth', 'verified'])->name('calendar.editar');

Route::post('/calendar/actualizar/{activity}', [App\Http\Controllers\ActivityController::class, 'update'])->middleware(['auth', 'verified'])->name('calendar.actualizar');

Route::post('/calendar/borrar/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->middleware(['auth', 'verified'])->name('calendar.borrar');


// Rutas del perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
