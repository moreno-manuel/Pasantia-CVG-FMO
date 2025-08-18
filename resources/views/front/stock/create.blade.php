@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/camera/camera_inventories/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-zinc-600 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-600">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Crear Nuevo Equipo</h1>

                <!-- Botón Volver -->
                <a href="{{ route('stock.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('stock.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo equipment -->
                    <div>
                        <label for="equipment" class="block text-sm font-semibold text-white">Tipo de Equipo</label>
                        <select name="equipment" id="equipment"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('equipment') border-red-500 @enderror"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($equipments as $equipment)
                                <option value="{{ $equipment }}" {{ old('equipment') == $equipment ? 'selected' : '' }}>
                                    {{ $equipment }}
                                </option>
                            @endforeach
                            <option value="Otro" {{ old('equipment') == 'Otro' ? 'selected' : '' }}>Otro
                        </select>
                        @error('equipment')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo para equipo personalizado -->
                    <div id="other-eq-field" class="hidden">
                        <label for="other_eq" class="block text-sm font-semibold text-white">Especifica el Tipo de
                            Equipo</label>
                        <input type="text" name="other_eq" id="other_eq" value="{{ old('other_eq') }}" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('other_eq') border-red-500 @enderror">
                        @error('other_eq')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-semibold text-white">Dirección MAC</label>
                        <input type="text" name="mac" id="mac" minlength="12" maxlength="12"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('mac') border-red-500 @enderror"
                            value="{{ old('mac') }}" placeholder="Ejemplo: 001A2B3C4D5E" required>
                        @error('mac')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="marca" class="block text-sm font-semibold text-white">Marca</label>
                        <input type="text" name="mark" id="mark" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('mark') border-red-500 @enderror"
                            value="{{ old('mark') }}" placeholder="Especifique la marca" required>
                        @error('mark')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-semibold text-white">Modelo</label>
                        <input type="text" name="model" id="model" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('model') border-red-500 @enderror"
                            value="{{ old('model') }}" required>
                        @error('model')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Nota de Entrega -->
                    <div>
                        <label for="delivery_note" class="block text-sm font-semibold text-white">Nota de Entrega</label>
                        <input type="text" name="delivery_note" id="delivery_note" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('delivery_note') border-red-500 @enderror"
                            value="{{ old('delivery_note') }}" required>
                        @error('delivery_note')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente esta cámara..." required>{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" onclick="return confirm('¿Está seguro de guardar este Equipo?')"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Guardar Equipo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
