<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirige al calendario si está autenticado, de lo contrario, se redirige al login automáticamente
    return redirect()->route('dashboard');
})->middleware('auth');


// Rutas del CRUD de eventos
Route::get('/calendar', function () {
    return view('calendar');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/calendar/agregar', [App\Http\Controllers\EventoController::class, 'store'])->middleware(['auth', 'verified'])->name('calendar.agregar');

Route::post('/calendar/mostrar', [App\Http\Controllers\EventoController::class, 'show'])->middleware(['auth', 'verified'])->name('calendar.mostrar');

Route::post('/calendar/editar/{id}', [App\Http\Controllers\EventoController::class, 'edit'])->middleware(['auth', 'verified'])->name('calendar.editar');

Route::post('/calendar/actualizar/{evento}', [App\Http\Controllers\EventoController::class, 'update'])->middleware(['auth', 'verified'])->name('calendar.actualizar');

Route::post('/calendar/borrar/{id}', [App\Http\Controllers\EventoController::class, 'destroy'])->middleware(['auth', 'verified'])->name('calendar.borrar');


// Rutas del perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
