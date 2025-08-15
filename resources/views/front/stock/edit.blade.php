@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/stock/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-zinc-600 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-600">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Crear Nuevo Equipo</h1>

                <!-- Botón Volver -->
                <a href="{{ route('stock.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-zinc-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-zinc-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('stock.update', $eq->mac) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo equipment -->
                    <div>
                        <label for="equipment" class="block text-sm font-semibold text-white">Tipo de Equipo</label>
                        <select name="equipment" id="equipment"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('equipment') border-red-500 @enderror"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($equipments as $equipment)
                                <option value="{{ $equipment }}"
                                    {{ old('equipment', isset($eq) ? $eq->equipment : '') == $equipment ? 'selected' : '' }}>
                                    {{ $equipment }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment')
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
                                <option value="{{ $mark }}"
                                    {{ old('mark', isset($eq) ? $eq->mark : '') == $mark ? 'selected' : '' }}>
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
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('other_mark') border-red-500 @enderror"
                            placeholder="Nombre de la marca">
                        @error('other_mark')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-semibold text-white">Modelo</label>
                        <input type="text" name="model" id="model" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('model') border-red-500 @enderror"
                            value="{{ old('model', $eq->model) }}" required>
                        @error('model')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Nota de Entrega -->
                    <div>
                        <label for="delivery_note" class="block text-sm font-semibold text-white">Nota de Entrega</label>
                        <input type="text" name="delivery_note" id="delivery_note" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('delivery_note') border-red-500 @enderror"
                            value="{{ old('delivery_note', $eq->delivery_note) }}" required>
                        @error('delivery_note')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente esta cámara..." required>{{ old('description', $eq->description) }}</textarea>
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
