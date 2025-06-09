@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/link/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Nuevo Enlace</h1>

            <!-- Botón Volver -->
            <a href="{{ route('enlace.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>


        {{-- Formnulario Enlace (link) --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('enlace.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-medium text-gray-700">Dirección MAC</label>
                        <input type="text" name="mac" value="{{ old('mac') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
                            @error('mac') border-red-500 @enderror"
                            placeholder="Ejemplo: 001A2B3C4D5E" required>

                        @error('mac')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" value = '{{ old('name') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                            required>

                        @error('name')
                            <span class="text-red-500
                            text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                        <select name="mark" value='{{ old('mark') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona una marca</option>
                            <option value="Hikvision">Hikvision</option>
                            <option value="Dahua">Dahua</option>
                            <option value="Axis">Axis</option>
                            <option value="Ubiquiti">Ubiquiti</option>
                            <option value="Other">Otra</option>
                        </select>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="model" value="{{ old('model') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo SSID -->
                    <div>
                        <label for="ssid" class="block text-sm font-medium text-gray-700">SSID</label>
                        <input type="text" name="ssid" value="{{ old('ssid') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Nombre de red Wi-Fi" required>
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-medium text-gray-700">Dirección IP</label>
                        <input type="text" name="ip" value="{{ old('ip') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
                            @error('ip') border-red-500 @enderror"
                            placeholder="Ejemplo: 192.168.1.20" required>

                        @error('ip')
                            <span class="text-red-500
                            text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700">Localidad</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" value = '{{ old('status') }}'
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
                        <textarea name="description" value="{{ old('description') }}" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este enlace..."></textarea>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150"
                        onclick="return confirm('¿Estás seguro de guardar este Enlace?')">
                        Guardar Enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
