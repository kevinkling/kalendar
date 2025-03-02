<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

$authMiddleware = env('AUTH_ENABLED', true) ? ['auth', 'verified'] : [];

Route::get('/', function () {
    // Redirige al calendario si está autenticado, de lo contrario, se redirige al login automáticamente
    return redirect()->route('dashboard');
})->middleware($authMiddleware );

// Rutas del CRUD de actividades

Route::get('/calendar', function () {
    return view('calendar');
})->middleware($authMiddleware)->name('dashboard');

Route::post('/calendar/agregar', [App\Http\Controllers\ActivityController::class, 'store'])->middleware($authMiddleware)->name('calendar.agregar');

Route::get('/calendar/mostrar', [App\Http\Controllers\ActivityController::class, 'show'])->middleware($authMiddleware)->name('calendar.mostrar');

Route::post('/calendar/editar/{id}', [App\Http\Controllers\ActivityController::class, 'edit'])->middleware($authMiddleware)->name('calendar.editar');

Route::post('/calendar/actualizar/{activity}', [App\Http\Controllers\ActivityController::class, 'update'])->middleware($authMiddleware)->name('calendar.actualizar');

Route::post('/calendar/borrar/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->middleware($authMiddleware)->name('calendar.borrar');



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
