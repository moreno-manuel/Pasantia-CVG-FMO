@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/attention/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Editar Condición de Atención</h1>

                <a href="{{ route('atencion.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('atencion.update', $condition) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo nombre de condicion no editable-->
                    <div>
                        <label for="name" class="block text-sm font-medium text-white">Condicion de Atención</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('name', $condition->name) }}" readonly disabled>
                    </div>

                    <!-- Campo camara ID -->
                    <div>
                        <label for="camera_id" class="block text-sm font-medium text-white">Cámara</label>
                        <input type="text" name="camera_id" id="camera_id"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('camera_id', $condition->camera->name) }}" readonly disabled>
                    </div>

                    <!-- Campo Fecha de Inicio -->
                    <div class="mb-4">
                        <label for="date_ini" class="block text-sm font-semibold text-white">Fecha de inicio</label>
                        <input type="date" name="date_ini" id="date_ini" max="{{ now()->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('date_ini', $condition->date_ini) }}" required disabled>
                    </div>

                    <!-- Campo Fecha de Finalización -->
                    <div class="mb-4">
                        <label for="date_end" class="block text-sm font-semibold text-white">Fecha de Realización</label>
                        <input type="date" name="date_end" id="date_end" min="{{ now()->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('date_end', $condition->date_end) }}">
                    </div>


                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" value="{{ old('description') }}" rows="3"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente la condición de atención..."required></textarea>
                        <label for="date_end" class="block text-xs font-semibold text-green-400">útlima descripción generada
                            el
                            ({{ $condition->description()->latest()->value('created_at')->format('Y/m/d') }})</label>
                    </div>

                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Estás seguro de actualizar datos de condición?')">
                        Guardar Condición
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
