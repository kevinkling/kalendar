@extends('layouts.app')

@section('calendar')
<div class="container mx-auto h-[90vh] flex items-center justify-center p-4">
    <div id='calendar' class="w-[85%] h-full rounded-xl shadow-lg border "></div>
</div>

<!-- Modal -->
<div id="eventoModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 space-y-4">
        <h2 id="modalTitle" class="text-xl font-semibold text-gray-800"></h2>

        <div id="errorContainer" class="text-red-500 text-sm"></div>

        <form id="form">

            @csrf <!-- Agregar el token de seguridad -->

            <div class="mb-4">
                <input type="hidden" id="id" name="id" class="mt-2 p-2 w-full border border-gray-300 rounded-md" />
            </div>

            <div class="mb-4">
                <label for="activity" class="block text-gray-700 text-sm">Tipo de actividad</label>
                <input type="text" id="activity" name="activity" class="mt-2 p-2 w-full border border-gray-300 rounded-md text-sm" required placeholder="Ejemplo: TP, Examen, Exposición..." />
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-gray-700 text-sm">Materia</label>
                <input type="text" id="subject" name="subject" class="mt-2 p-2 w-full border border-gray-300 rounded-md text-sm" required placeholder="Ejemplo: Programación, Diseño..." />
            </div>

            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 text-sm">Fecha de inicio (Hora que se abrió dicha tarea en el campus)</label>
                <input type="date" id="start_date" name="start_date" class="mt-2 p-2 w-full border border-gray-300 rounded-md text-sm" required />
            </div>

            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 text-sm">Fecha de fin (Fecha de entrega)</label>
                <input type="date" id="end_date" name="end_date" class="mt-2 p-2 w-full border border-gray-300 rounded-md text-sm" required />
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-gray-700 text-sm">Notas</label>
                <textarea id="notes" name="notes" class="mt-2 p-2 w-full border border-gray-300 rounded-md text-sm" rows="4" placeholder="Ejemplo: Cierra 23:59, Tarea individual..."></textarea>
            </div>

        </form>
        <div class="flex justify-end space-x-2 mt-4">
            <button type="submit" style="display: none;" id="btnGuardar" class="px-2 py-1.5 bg-green-700 text-white rounded-md text-sm">Guardar</button>
            <button type="submit" style="display: none;" id="btnEliminar" class="px-2 py-1.5 bg-red-500 text-white rounded-md text-sm">Eliminar</button>
            <button type="submit" style="display: none;" id="btnModificar" class="px-2 py-1.5 bg-yellow-500 text-white rounded-md text-sm">Modificar</button>
            <button type="button" id="closeModalBtn" class="px-2 py-1.5 bg-gray-300 rounded-md text-sm">Cancelar</button>
        </div>
    </div>
</div>

@endsection
