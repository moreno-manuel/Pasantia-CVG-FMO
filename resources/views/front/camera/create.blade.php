@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/camaras/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Nueva Cámara</h1>

            <!-- Botón Volver -->
            <a href="{{ route('camara.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        {{-- Formulario para camara --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="#" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-medium text-gray-700">Dirección MAC</label>
                        <input type="text" name="mac" id="mac"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ejemplo: 00:1A:2B:3C:4D:5E" required>
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
                        <input type="text" name="model" id="model"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo NVR ID -->
                    <div>
                        <label for="nvr_id" class="block text-sm font-medium text-gray-700">NVR</label>
                        <input type="text" name="nvr_id" id="nvr_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-medium text-gray-700">Dirección IP</label>
                        <input type="text" name="ip" id="ip"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ejemplo: 192.168.1.10" required>
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="ubicacion" class="block text-sm font-medium text-gray-700">Localidad</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona el Status</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente la ubicación o uso de esta cámara..."></textarea>
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
