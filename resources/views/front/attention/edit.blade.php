@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/attention/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-zinc-600 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-600">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Editar Condición de Atención</h1>

                <!-- Botón Volver -->
                <div class="flex justify-end items-center mb-6">
                    <a href="{{ session('url') }}"
                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Volver
                    </a>
                </div>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('atencion.update', $condition) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Fecha de Finalización -->
                    <div class="mb-4">
                        <label for="date_end" class="block text-sm font-semibold text-white">Fecha de Realización</label>
                        <input type="date" name="date_end" id="date_end" min="{{ now()->format('Y-m-d') }}"
                            class="custom-date-icon mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('date_end', $condition->date_end) }}">
                    </div>


                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-white">Control de condición</label>
                        <textarea name="description" id="description" value="{{ old('description') }}" rows="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('description') border-red-500 @enderror"
                            placeholder="Agrega nueva descripción para el control de seguimiento"></textarea>
                        @php
                            // Obtener el último registro relacionado
                            $latestControl = $condition->controlCondition()->latest()->first();
                        @endphp
                        <span
                            class=" {{ $latestControl ? 'text-green-400' : 'text-yellow-500' }} block text-xs font-semibold">
                            {{ $latestControl
                                ? 'Última descripción agregada (' . \Carbon\Carbon::parse($latestControl->created_at)->format('d/m/Y - H:i:s') . ')'
                                : 'No se ha generado una descripción de seguimiento' }}
                        </span>
                        @error('description')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Estás seguro de actualizar datos de condición?')">
                        Guardar Condición
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
