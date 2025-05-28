@extends('layouts.app-home')
@section('content')
    <!-- resources/views/link/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Editar Enlace</h1>

            <!-- Botón Volver -->
            <a href="{{ route('enlace.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        {{-- Formulario de edicion --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('enlace.update', $link->mac) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Campo Dirección MAC (no editable) -->
                <div>
                    <label for="mac" class="block text-sm font-medium text-gray-700">Dirección MAC</label>
                    <input type="text" name="mac" id="mac"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-100"
                        value="{{ $link->mac }}" readonly disabled>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('name', $link->name) }}" required>
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="mark" class="block text-sm font-medium text-gray-700">Marca</label>
                        <select name="mark" id="mark"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona una marca</option>
                            <option value="Hikvision" {{ $link->mark == 'Hikvision' ? 'selected' : '' }}>Hikvision
                            </option>
                            <option value="Dahua" {{ $link->mark == 'Dahua' ? 'selected' : '' }}>Dahua</option>
                            <option value="Axis" {{ $link->mark == 'Axis' ? 'selected' : '' }}>Axis</option>
                            <option value="Ubiquiti" {{ $link->mark == 'Ubiquiti' ? 'selected' : '' }}>Ubiquiti
                            </option>
                            <option value="Other" {{ $link->mark == 'Other' ? 'selected' : '' }}>Otra</option>
                        </select>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="model" id="model"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('model', $link->model) }}" required>
                    </div>

                    <!-- Campo SSID -->
                    <div>
                        <label for="ssid" class="block text-sm font-medium text-gray-700">SSID</label>
                        <input type="text" name="ssid" id="ssid"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('ssid', $link->ssid) }}">
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-medium text-gray-700">Dirección IP</label>
                        <input type="text" name="ip" id="ip"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('ip', $link->ip) }}" required>
                    </div>
                    @error('ip')
                        <br>
                        <span class="bg-red-600 text-white py-2 px-4 rounded font-bold"
                            style="font-size: 12px">{{ $message }}</span>
                        </br>
                    @enderror

                    <!-- Campo Localidad -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Localidad</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('location', $link->location) }}" required>
                    </div>

                    <!-- Campo Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona el estado</option>
                            <option value="Activo" {{ $link->status === 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ $link->status === 'Inactivo' ? 'selected' : '' }}>Inactivo
                            </option>
                            <option value="Mantenimiento" {{ $link->status === 'Mantenimiento' ? 'selected' : '' }}>
                                En Mantenimiento</option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este enlace...">{{ old('description', $link->description) }}</textarea>
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Actualizar Enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
