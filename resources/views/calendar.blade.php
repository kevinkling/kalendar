@extends('layouts.app')

@section('calendar')
<div class="container mx-auto h-[90vh]  flex items-center justify-center">
    <div id='calendar' class="w-full h-full"></div>
</div>

<!-- Modal -->
<div id="eventoModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6 space-y-4">
        <h2 id="modalTitle" class="text-xl font-semibold text-gray-800">Nuevo Evento</h2>

        <form id="eventoForm">
            <div class="space-y-2">
                <div>
                    <label for="materia" class="block text-gray-700">Materia</label>
                    <input type="text" id="materia" name="materia" required
                           class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="hora" class="block text-gray-700">Hora (opcional)</label>
                    <input type="time" id="hora" name="hora"
                           class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="nota" class="block text-gray-700">Nota (opcional)</label>
                    <textarea id="nota" name="nota" rows="3"
                              class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded-md">Cancelar</button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md">Guardar</button>
            </div>
        </form>
    </div>
</div>

@endsection
