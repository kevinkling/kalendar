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


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/check-db', function () {
    // Obtener las tablas de la base de datos
    $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');

    // Verificar si la tabla sessions existe
    $sessionsExists = in_array('sessions', array_column($tables, 'name'));

    // Obtener el contenido de la tabla sessions
    $sessions = $sessionsExists ? DB::table('sessions')->get() : 'La tabla sessions no existe';

    // Obtener el estado de las migraciones
    $migrations = DB::table('migrations')->get();

    return response()->json([
        'tables' => $tables, // Listado de todas las tablas
        'sessions_exists' => $sessionsExists, // Si la tabla sessions existe
        'sessions_data' => $sessions, // Datos de la tabla sessions
        'migrations' => $migrations, // Información de las migraciones
    ]);
});


require __DIR__.'/auth.php';
