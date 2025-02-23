@extends('layouts.app')

@section('calendar')
<div class="container mx-auto h-[90vh]  flex items-center justify-center">
    <div id='calendar' class="w-full h-full"></div>
</div>

<!-- Modal -->
<div id="eventoModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-120 p-6 space-y-4">
        <h2 id="modalTitle" class="text-xl font-semibold text-gray-800"></h2>

        <form id="form">

            @csrf <!-- Agregar el token de seguridad -->

            <!-- ID -->
            <div class="mb-4">
                <label for="id" class="block text-gray-700">ID del Evento</label>
                <input type="text" id="id" name="id" class="mt-2 p-2 w-full border border-gray-300 rounded-md" required />
            </div>

            <!-- Título -->
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Título</label>
                <input type="text" id="title" name="title" class="mt-2 p-2 w-full border border-gray-300 rounded-md" required />
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Descripción</label>
                <textarea id="description" name="description" class="mt-2 p-2 w-full border border-gray-300 rounded-md" rows="4"></textarea>
            </div>

            <!-- Fecha de inicio -->
            <div class="mb-4">
                <label for="start" class="block text-gray-700">Fecha de Inicio</label>
                <input type="date" id="start" name="start" class="mt-2 p-2 w-full border border-gray-300 rounded-md" required />
            </div>

            <!-- Fecha de fin -->
            <div class="mb-4">
                <label for="end" class="block text-gray-700">Fecha de Fin</label>
                <input type="date" id="end" name="end" class="mt-2 p-2 w-full border border-gray-300 rounded-md" required />
            </div>


        </form>
        <div class="flex justify-end space-x-2 mt-4">
            <button type="submit" id="btnGuardar" class="px-2 py-2 bg-green-700 text-white rounded-md">Guardar</button>
            <button type="submit" id="btnModificar" class="px-2 py-2 bg-yellow-500 text-white rounded-md">Modificar</button>
            <button type="submit" id="btnEliminar" class="px-2 py-2 bg-red-500 text-white rounded-md">Eliminar</button>
            <button type="button" id="closeModalBtn" class="px-2 py-2 bg-gray-300 rounded-md">Cancelar</button>
        </div>

    </div>
</div>

@endsection
