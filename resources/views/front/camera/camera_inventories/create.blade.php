@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/camera/camera_inventories/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Nueva Cámara</h1>

            <!-- Botón Volver -->
            <a href="{{ route('inventories.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>


        {{-- Formulario para camara --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('inventories.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-medium text-gray-700">Dirección MAC</label>
                        <input type="text" name="mac" id="mac" value="{{ old('mac') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('mac') border-red-500 @enderror"
                            placeholder="Ejemplo: 001A2B3C4D5E" required>

                        @error('mac')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                        <select name="mark" id="mark"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona una marca</option>
                            <option value="Hikvision">Hikvision</option>
                            <option value="Dahua">Dahua</option>
                            <option value="Axis">Axis</option>
                            <option value="Other">Otra</option>
                        </select>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nota de Entrega</label>
                        <input type="text" name="delivery_note" id="delivery_note" value ="{{ old('delivery_note') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="ubicacion" class="block text-sm font-medium text-gray-700">Destino de
                            Instalación</label>
                        <input type="text" name="destination" id="destination" value="{{ old('destination') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" value="{{ old('description') }}" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente esta cámara..."></textarea>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Guardar Cámara
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
