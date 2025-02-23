<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Carbon\Carbon; // Darle formato a las fechas

class EventoController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        request()->validate(Evento::$rules); // Valido las reglas que definí en el modelo Evento
        $evento = Evento::create($request->all()); // Creo un nuevo evento con los datos que me llegan por el request
    }

    public function show(Evento $evento)
    {
        $evento = Evento::all(); // Obtengo todos los eventos
        return response()->json($evento); // Devuelvo los eventos en formato JSON
    }

    public function edit($id)
    {
        $evento = Evento::find($id); // Busco el evento por su ID

        $evento->start = Carbon::parse($evento->start)->format('Y-m-d');
        $evento->end = Carbon::parse($evento->end)->format('Y-m-d');

        return response()->json($evento); // Devuelvo el evento en formato JSON
    }

    public function update(Request $request, Evento $evento)
    {
        request()->validate(Evento::$rules); // Valido las reglas que definí en el modelo Evento
        $evento->update($request->all()); // Actualizo el evento con los datos que me llegan por el request



        return response()->json($evento); // Devuelvo el evento actualizado en formato JSON
    }

    public function destroy($id)
    {
        $evento = Evento::find($id)->delete(); // Busco el evento por su ID y lo elimino

        return response()->json($evento); // Devuelvo el evento eliminado en formato JSON
    }
}
