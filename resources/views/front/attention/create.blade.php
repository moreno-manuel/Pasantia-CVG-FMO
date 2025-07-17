@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/attention/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Crear Nueva Condición de Atención</h1>

                <!-- Botón Volver -->
                <a href="{{ route('atencion.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('atencion.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Nombre Condición de Atencion -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-white">Condicion de Atención</label>
                        <select name="name" id="name"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm "
                            required>
                            <option value="">Selecciona...</option>
                            @foreach ($names as $name)
                                <option value="{{ $name }}" {{ old('name') == $name ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="other-condition-field" class="hidden">
                        <label for="other_condition" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="other_condition" id="other_condition"
                            value="{{ old('other_condition') }}" minlength="5"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('other_condition') border-red-500 @enderror"
                            placeholder="Especifica el nombre de la condición">
                        @error('other_condition')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo camara ID -->
                    <div>
                        <label for="camera_id" class="block text-sm font-semibold text-white">Cámara</label>
                        <select name="camera_id" id="camera_id"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm  @error('camera_id') border-red-500 @enderror"
                            required>
                            <option value="">Selecciona...</option>
                            @foreach ($cameras as $camera)
                                <option value="{{ $camera->id }}" {{ old('camera_id') == $camera->id ? 'selected' : '' }}>
                                    {{ $camera->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('camera_id')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Fecha de Inicio -->
                    <div class="mb-4">
                        <label for="date_ini" class="block text-sm font-semibold text-white">Fecha de inicio</label>
                        <input type="date" name="date_ini" id="date_ini" max="{{ now()->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('date_ini') border-red-500 @enderror"
                            value="{{ old('date_ini') }}" required>

                        @error('date_ini')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Fecha de Finalización -->
                    <div class="mb-4">
                        <label for="date_end" class="block text-sm font-semibold text-white">Fecha de Realización</label>
                        <input type="date" name="date_end" id="date_end" min="{{ now()->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('date_end') }}">
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" value="{{ old('description') }}" rows="3"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente la condición de atención..." required></textarea>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Estás seguro de guardar esta condición?')">
                        Guardar Condición
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- personaliza nombre  --}}
    @push('script')
        <script>
            const select = document.getElementById('name');
            const otherField = document.getElementById('other-condition-field');

            if (select && otherField) {
                select.addEventListener('change', function() {
                    otherField.classList.toggle('hidden', select.value !== 'OTROS');
                });

                if (select.value === 'OTRO') {
                    otherField.classList.remove('hidden');
                }
            }
        </script>
    @endpush
@endsection
