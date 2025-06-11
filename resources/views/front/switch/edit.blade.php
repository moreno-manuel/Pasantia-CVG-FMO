@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/switch/edit.blade.php -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Editar Switch</h1>

                <!-- Botón Volver -->
                <a href="{{ route('switch.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('switch.update', $switch) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Serial (no editable) -->
                    <div>
                        <label for="serial" class="block text-sm font-semibold text-white">Serial</label>
                        <input type="text" name="serial" id="serial"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('serial', $switch->serial) }}" readonly disabled>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-semibold text-white">Modelo</label>
                        <input type="text" name="model" value="{{ old('model', $switch->model) }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Número de Puertos -->
                    <div>
                        <label for="number_ports" class="block text-sm font-semibold text-white">Número de Puertos</label>
                        <input type="number" name="number_ports" id="number_ports"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" max="48" value="{{ old('number_ports', $switch->number_ports) }}" required>
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="location" class="block text-sm font-semibold text-white">Localidad</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('location', $switch->location) }}" required>
                    </div>

                    <!-- Campo Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-white">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            <option value="Activo" {{ old('status', $switch->status) == 'Activo' ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="Inactivo" {{ old('status', $switch->status) == 'Inactivo' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este switch...">{{ old('description', $switch->description) }}</textarea>
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Estás seguro de actualizar este Switch?')">
                        Actualizar Switch
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
