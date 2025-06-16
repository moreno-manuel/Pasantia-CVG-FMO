@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/edit.blade.php -->


    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Editar NVR</h1>

                <!-- Botón Volver -->
                <a href="{{ route('nvr.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('nvr.update', $nvr) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Dirección MAC (no editable) -->
                    <div>
                        <label for="mac" class="block text-sm font-semibold text-white">Dirección MAC</label>
                        <input type="text" name="mac" id="mac" value="{{ $nvr->mac }}"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            readonly disabled>
                    </div>

                    <!-- Campo Nombre (no editable) -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ $nvr->name }}"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            readonly disabled>
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
                                    {{ old('mark', isset($nvr) ? $nvr->mark : '') == $mark ? 'selected' : '' }}>
                                    {{ $mark }}
                                </option>
                            @endforeach
                            <option value="Otra">Otra</option>
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
                            value="{{ old('model', $nvr->model) }}" required>
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-semibold text-white">Dirección IP</label>
                        <input type="text" name="ip" id="ip"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('ip', $nvr->ip) }}" required>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div>
                        <label for="ports_number" class="block text-sm font-semibold text-white">N° de Puertos</label>
                        <input type="number" name="ports_number" id="ports_number"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" max="64" value="{{ old('ports_number', $nvr->ports_number) }}" required>
                    </div>

                    <!-- Campo Número de Volumen (No editable) -->
                    <div>
                        <label for="slot_number" class="block text-sm font-semibold text-white">N° de Volumen</label>
                        <input type="number" name="slot_number" id="slot_number"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" max="4" value="{{ old('slot_number', $nvr->slot_number) }}" readonly
                            disabled>
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="location" class="block text-sm font-semibold text-white">Localidad</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('location', $nvr->location) }}" required>
                    </div>

                    <!-- Campo Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-white">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            <option value="Activo" {{ old('status', $nvr->status) == 'Activo' ? 'selected' : '' }}>Activo
                            </option>
                            <option value="Inactivo" {{ old('status', $nvr->status) == 'Inactivo' ? 'selected' : '' }}>
                                Inactivo</option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este NVR...">{{ old('description', $nvr->description) }}</textarea>
                    </div>
                </div>

                <!-- Campos dinámicos de HDDs -->
                <div id="hdd-form-container" class="mt-6">
                    <h3 class="text-base font-medium text-white mb-2">Volumen</h3>
                    <p class="text-sm text-gray-300 mb-4">Ingresa la información del Volumen y/o Disco Duro</p>

                    <div class="space-y-4" id="hdd-fields">
                        @foreach ($nvr->slotNvr as $index => $slot)
                            <div class="bg-gray-700 p-4 rounded-md border border-gray-600 mb-4">
                                <h4 class="text-sm font-medium text-white mb-2">Volumen #{{ $index + 1 }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                    <!-- Serial del disco -->
                                    <div>
                                        <label class="block text-sm font-medium text-white">Serial</label>
                                        <input type="text" name="volumen[{{ $index }}][serial_disco]"
                                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm    @error('volumen.' . $index . '.serial_disco') border-red-500 @enderror"
                                            value="{{ old("volumen.$index.serial_disco", $slot['hdd_serial']) }}">
                                        @error('volumen.' . $index . '.serial_disco')
                                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Capacidad del disco -->
                                    <div>
                                        <label class="block text-sm font-medium text-white">Capacidad/Disco (TB)</label>
                                        <input type="number" name="volumen[{{ $index }}][capacidad_disco]"
                                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm   @error('volumen.' . $index . '.capacidad_disco') border-red-500 @enderror"
                                            min="1"
                                            value="{{ old("volumen.$index.capacidad_disco", $slot['hdd_capacity']) }}">
                                        @error('volumen.' . $index . '.capacidad_disco')
                                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Capacidad máxima del volumen (no editable) -->
                                    <div>
                                        <label class="block text-sm font-medium text-white">Capacidad Máxima/volumen
                                            (TB)
                                        </label>
                                        <input type="number" name="volumen[{{ $index }}][capacidad_max_volumen]"
                                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm   "
                                            min="1"
                                            value="{{ old("volumen.$index.capacidad_max_volumen", $slot['capacity_max']) }}"
                                            readonly disabled>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" onclick="return confirm('¿Estás seguro de actualizar Nvr?')"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Actualizar NVR
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- para el campo nueva marca --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const markSelect = document.getElementById('mark');
                const otherBrandField = document.getElementById('other-brand-field');

                markSelect.addEventListener('change', function() {
                    if (markSelect.value === 'Otra') {
                        otherBrandField.classList.remove('hidden');
                    } else {
                        otherBrandField.classList.add('hidden');
                    }
                });

                // Si ya se había seleccionado "Otra" previamente (ej: al regresar por errores)
                if (markSelect.value === 'Otra') {
                    otherBrandField.classList.remove('hidden');
                }
            });
        </script>
    @endpush
@endsection
