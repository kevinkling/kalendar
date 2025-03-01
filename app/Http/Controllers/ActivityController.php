<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        // Validación usando las reglas definidas en el modelo
        $validatedData = $request->validate(Activity::$rules);

        // Creación de la actividad usando 'fill' para asignar todos los campos de una vez
        $activity = Activity::create($validatedData);

        return response()->json($activity, 201); // Retorna la actividad creada con código 201
    }

    public function show(Request $request)
    {
        $activities = Activity::all();

        return response()->json($activities);
    }

    public function edit($id)
    {
        // Buscar la actividad y devolver un error si no se encuentra
        $activity = Activity::findOrFail($id);
        return response()->json($activity);
    }

    public function update(Request $request, $id)
    {
        // Validación usando las reglas definidas en el modelo
        $validatedData = $request->validate(Activity::$rules);

        // Buscar la actividad
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        // Actualizar los datos de la actividad
        $activity->update($validatedData);

        return response()->json($activity);
    }

    public function destroy($id)
    {
        // Buscar la actividad y devolver un error si no se encuentra
        $activity = Activity::findOrFail($id);

        $activity->delete();
        return response()->json(['message' => 'Activity deleted']);
    }
}
