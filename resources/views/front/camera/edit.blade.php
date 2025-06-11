@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/camera/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Editar Cámara</h1>

                <!-- Botón Volver -->
                <a href="{{ route('camara.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('camara.update', $camera->mac) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Dirección MAC (no editable) -->
                    <div>
                        <label for="mac" class="block text-sm font-semibold text-white">Dirección MAC</label>
                        <input type="text" name="mac" id="mac"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ $camera->mac }}" readonly disabled>
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="mark" class="block text-sm font-semibold text-white">Marca</label>
                        <select name="mark" id="mark"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($marks as $mark)
                                <option value="{{ $mark }}"
                                    {{ old('mark', isset($camera) ? $camera->mark : '') == $mark ? 'selected' : '' }}>
                                    {{ $mark }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo para marca personalizada -->
                    <div id="other-brand-field" class="hidden">
                        <label for="other_brand" class="block text-sm font-semibold text-white">Especifica la marca</label>
                        <input type="text" name="other_mark" id="other_mark" value="{{ old('other_mark') }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('other_mark') border-red-500 @enderror"
                            placeholder="Nombre de la marca">
                        @error('other_mark')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-semibold text-white">Modelo</label>
                        <input type="text" name="model" id="model"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('model', $camera->model) }}" required>
                    </div>

                    <!-- Campo NVR ID -->
                    <div>
                        <label for="nvr_id" class="block text-sm font-medium text-white">NVR</label>
                        <select name="nvr_id" id="nvr_id"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione...</option>
                            @foreach ($nvrs as $nvr)
                                <option value="{{ $nvr->mac }}"
                                    {{ old('nvr_id', isset($camera) ? $camera->nvr_id : '') == $nvr->mac ? 'selected' : '' }}>
                                    {{ $nvr->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                            value="{{ old('name', $camera->name) }}" required>
                        @error('name')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-semibold text-white">Dirección IP</label>
                        <input type="text" name="ip" id="ip"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('ip') border-red-500 @enderror"
                            value="{{ old('ip', $camera->ip) }}" required>
                        @error('ip')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="location" class="block text-sm font-semibold text-white">Localidad</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('location', $camera->location) }}" required>
                    </div>

                    <!-- Campo Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-white">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona el Status</option>
                            <option value="Activo" {{ $camera->status === 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ $camera->status === 'Inactivo' ? 'selected' : '' }}>Inactivo
                            </option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este enlace...">{{ old('description', $camera->description) }}</textarea>
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Actualizar Cámara
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- para el campo marca  --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('mark');
                const otherField = document.getElementById('other-brand-field');

                select.addEventListener('change', function() {
                    if (select.value === 'OTRA') {
                        otherField.classList.remove('hidden');
                    } else {
                        otherField.classList.add('hidden');
                    }
                });

                // Mostrar campo si ya se había seleccionado "Other" (ej: tras error de validación)
                if (select.value === 'OTRA') {
                    otherField.classList.remove('hidden');
                }
            });
        </script>
    @endpush
@endsection
