@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/attention/edit.blade.php -->
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Editar Condición de Atención</h1>

            <!-- Botón Volver -->
            <a href="{{ route('atencion.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <!-- Mensaje singular o plural -->
                @if ($errors->count() === 1)
                    <strong class="font-bold">Por favor corrige el siguiente error:</strong>
                @else
                    <strong class="font-bold">Por favor corrige los siguientes errores:</strong>
                @endif

                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('atencion.update', $condition) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo nombre de condicion no editable-->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Condicion de Atención</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('name', $condition->name) }}" readonly disabled>
                    </div>

                    <!-- Campo camara ID -->
                    @php
                        $camera = $condition->camera;
                    @endphp
                    <div>
                        <label for="camera_id" class="block text-sm font-medium text-gray-700">Cámara</label>
                        <input type="text" name="camera_id" id="camera_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('camera_id', $camera->name) }}" readonly disabled>
                    </div>

                    <!-- Campo Fecha de Inicio -->
                    <div class="mb-4">
                        <label for="date_ini" class="block text-gray-700 font-medium mb-2">Fecha de inicio</label>
                        <input type="date" name="date_ini" id="date_ini" max="{{ now()->format('Y-m-d') }}"
                            value="{{ old('date_ini', $condition->date_ini) }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Campo Fecha de Finalización -->
                    <div class="mb-4">
                        <label for="date_end" class="block text-gray-700 font-medium mb-2">Fecha de finalización</label>
                        <input type="date" name="date_end" id="date_end"
                            value="{{ old('date_end', $condition->date_end) }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este switch...">{{ old('description', $condition->description) }}</textarea>
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Actualizar Condición de Atención
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
