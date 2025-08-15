@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/link/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-zinc-600 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-600">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Crear Nuevo Enlace</h1>

                <!-- Botón Volver -->
                <a href="{{ route('enlace.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('enlace.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-semibold text-white">Dirección MAC</label>
                        <input type="text" name="mac" value="{{ old('mac') }}" minlength="12" maxlength="12"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('mac') border-red-500 @enderror"
                            placeholder="Ejemplo: 001A2B3C4D5E" required>
                        @error('mac')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="mark" class="block text-sm font-semibold text-white">Marca</label>
                        <select name="mark" id="mark"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($marks as $mark)
                                <option value="{{ $mark }}" {{ old('mark') == $mark ? 'selected' : '' }}>
                                    {{ $mark }}
                                </option>
                            @endforeach
                            <option value="Otra" {{ old('mark') == 'Otra' ? 'selected' : '' }}>Otra
                        </select>
                    </div>

                    <!-- Campo para marca personalizada -->
                    <div id="other-brand-field" class="hidden">
                        <label for="other_brand" class="block text-sm font-semibold text-white">Especifica la marca</label>
                        <input type="text" name="other_mark" id="other_mark" value="{{ old('other_mark') }}"
                            minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('other_mark') border-red-500 @enderror"
                            placeholder="Nombre de la marca">
                        @error('other_mark')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-semibold text-white">Modelo</label>
                        <input type="text" name="model" value="{{ old('model') }}" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('model') border-red-500 @enderror"
                            required>
                        @error('model')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Campo Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" minlength="5"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Campo SSID -->
                    <div>
                        <label for="ssid" class="block text-sm font-semibold text-white">SSID</label>
                        <input type="text" name="ssid" value="{{ old('ssid') }}" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('ssid') border-red-500 @enderror"
                            placeholder="Nombre de red Wi-Fi" required>
                        @error('ssid')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-semibold text-white">Dirección IP</label>
                        <input type="text" name="ip" value="{{ old('ip') }}"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('ip') border-red-500 @enderror"
                            placeholder="Ejemplo: 192.168.1.20" required>
                        @error('ip')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Campo localidad -->
                    <div>
                        <label for="location" class="block text-sm font-semibold text-white">Localidad</label>
                        <select name="location" id="location"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location }}" {{ old('location') == $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                            <option value="Otra" {{ old('location') == 'Otra' ? 'selected' : '' }}>Otra
                            </option>
                        </select>
                    </div>

                    <!-- Campo Especificar Otra localidad -->
                    <div id="other-location-field" class="hidden">
                        <label for="other_location" class="block text-sm font-semibold text-white">Especifica la
                            localidad</label>
                        <input type="text" name="other_location" id="other_location" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('other_location') border-red-500 @enderror"
                            placeholder="Nombre de la localidad" value="{{ old('other_location') }}">
                        @error('other_location')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este enlace...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Estás seguro de guardar este Enlace?')">
                        Guardar Enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
