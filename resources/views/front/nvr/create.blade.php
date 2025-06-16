@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Crear Nuevo NVR</h1>

                <!-- Botón Volver -->
                <a href="{{ route('nvr.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('nvr.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-semibold text-white">Dirección MAC</label>
                        <input type="text" name="mac" id="mac"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('mac') border-red-500 @enderror"
                            value="{{ old('mac') }}" placeholder="Ejemplo: 001A2B3C4D5E" required>
                        @error('mac')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="mark" class="block text-sm font-semibold text-white">Marca</label>
                        <select name="mark" id="mark"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($marks as $mark)
                                <option value="{{ $mark }}" {{ old('mark') == $mark ? 'selected' : '' }}>
                                    {{ $mark }}
                                </option>
                            @endforeach
                            <option value="Otra">Otra</option>
                        </select>
                    </div>

                    <!-- Campo Especificar Otra marca -->
                    <div id="other-brand-field" class="hidden md:col-span-2">
                        <label for="other_brand" class="block text-sm font-semibold text-white">Especifica la marca</label>
                        <input type="text" name="other_mark" id="other_mark"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Nombre de la marca" value="{{ old('other_mark') }}" required>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-semibold text-white">Modelo</label>
                        <input type="text" name="model" id="model"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('model') }}" required>
                    </div>

                    <!-- Campo Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-semibold text-white">Dirección IP</label>
                        <input type="text" name="ip" id="ip"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('ip') border-red-500 @enderror"
                            value="{{ old('ip') }}" placeholder="Ejemplo: 192.168.1.20" required>
                        @error('ip')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div>
                        <label for="ports_number" class="block text-sm font-semibold text-white">N° de Puertos</label>
                        <input type="number" name="ports_number" id="ports_number"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" max="64" value="{{ old('ports_number') }}" required>
                    </div>

                    <!-- Campo Número de Volumen -->
                    <div>
                        <label for="slot_number" class="block text-sm font-semibold text-white">N° de Volumen</label>
                        <input type="number" name="slot_number" id="slot_number"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" max="4" oninput="generateHDDForm(this.value)"
                            value="{{ old('slot_number') }}" required>
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="location" class="block text-sm font-semibold text-white">Localidad</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('location') }}" required>
                    </div>

                    <!-- Campo Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-white">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            <option value="Activo" {{ old('status') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('status') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este NVR...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Campos dinámicos de HDDs -->
                <div id="hdd-form-container" class="mt-6">
                    <h3 class="text-base font-medium text-white mb-2">Volumen</h3>
                    <p class="text-sm text-gray-300 mb-4">Ingresa la información del Volumen y/o Disco Duro</p>
                    <div class="space-y-4" id="hdd-fields">
                        <!-- Campos generados dinámicamente -->
                        @php
                            $volumenData = old('volumen');
                        @endphp
                        @if ($volumenData && is_array($volumenData))
                            @foreach ($volumenData as $index => $volumen)
                                <div class="bg-gray-700 p-4 rounded-md border border-gray-600 mb-4">
                                    <h4 class="text-sm font-medium text-white mb-2">Volumen #{{ $loop->iteration }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Serial del disco -->
                                        <div>
                                            <label class="block text-sm font-medium text-white">Serial</label>
                                            <input type="text" name="volumen[{{ $index }}][serial_disco]"
                                                class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm 
                        @error('volumen.' . $index . '.serial_disco') border-red-500 @enderror"
                                                value="{{ $volumen['serial_disco'] ?? '' }}">
                                            @error('volumen.' . $index . '.serial_disco')
                                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Capacidad del disco -->
                                        <div>
                                            <label class="block text-sm font-medium text-white">Capacidad/Disco
                                                (TB)
                                            </label>
                                            <input type="number" name="volumen[{{ $index }}][capacidad_disco]"
                                                class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm 
                        @error('volumen.' . $index . '.capacidad_disco') border-red-500 @enderror"
                                                min="1" value="{{ $volumen['capacidad_disco'] ?? '' }}">
                                            @error('volumen.' . $index . '.capacidad_disco')
                                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Capacidad máxima del volumen -->
                                        <div>
                                            <label class="block text-sm font-medium text-white">Capacidad Máxima/volumen
                                                (TB)</label>
                                            <input type="number"
                                                name="volumen[{{ $index }}][capacidad_max_volumen]"
                                                class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm 
                        @error('volumen.' . $index . '.capacidad_max_volumen') border-red-500 @enderror"
                                                min="1" value="{{ $volumen['capacidad_max_volumen'] ?? '' }}"
                                                required>
                                            @error('volumen.' . $index . '.capacidad_max_volumen')
                                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" onclick="return confirm('¿Estás seguro de guardar este Nvr?')"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Guardar NVR
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

    {{-- para los campos de slot --}}
    @push('scripts')
        <script>
            function generateHDDForm(count) {
                const container = document.getElementById('hdd-fields');
                count = parseInt(count);
                if (isNaN(count) || count <= 0) return;

                // Limpiar contenido anterior
                container.innerHTML = '';

                // Recuperar datos anteriores si existen
                const existingData = @json(old('volumen'));

                for (let i = 0; i < count; i++) {
                    const data = existingData?.[i] || {};
                    const index = i;
                    const number = i + 1;

                    const html = `
            <div class="bg-gray-700 p-4 rounded-md border border-gray-600 mb-4">
                <h4 class="text-sm font-medium text-white mb-2">Volumen #${number}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Serial del disco -->
                    <div>
                        <label class="block text-sm font-medium text-white">Serial</label>
                        <input type="text" name="volumen[${index}][serial_disco]"
                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <span class="text-red-400 text-sm mt-1 hidden">Este campo es obligatorio</span>
                    </div>
                    <!-- Capacidad del disco -->
                    <div>
                        <label class="block text-sm font-medium text-white">Capacidad/Disco (TB)</label>
                        <input type="number" name="volumen[${index}][capacidad_disco]"
                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1">
                        <span class="text-red-400 text-sm mt-1 hidden">Este campo es obligatorio</span>
                    </div>
                    <!-- Capacidad máxima del volumen -->
                    <div>
                        <label class="block text-sm font-medium text-white">Capacidad Máxima/volumen (TB)</label>
                        <input type="number" name="volumen[${index}][capacidad_max_volumen]"
                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" required>
                        <span class="text-red-400 text-sm mt-1 hidden">Este campo es obligatorio</span>
                    </div>
                </div>
            </div>
        `;
                    container.insertAdjacentHTML('beforeend', html);
                }

                // Restaurar valores anteriores (si existen)
                if (existingData) {
                    for (let i = 0; i < existingData.length; i++) {
                        const vol = existingData[i];
                        document.querySelector(`input[name='volumen[${i}][serial_disco]']`).value = vol.serial_disco || '';
                        document.querySelector(`input[name='volumen[${i}][capacidad_disco]']`).value = vol.capacidad_disco ||
                            '';
                        document.querySelector(`input[name='volumen[${i}][capacidad_max_volumen]']`).value = vol
                            .capacidad_max_volumen || '';
                    }
                }
            }
        </script>
    @endpush
@endsection
