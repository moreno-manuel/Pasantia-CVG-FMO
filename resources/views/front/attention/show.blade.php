@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/attention/show.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Botón Volver -->
        <div class="flex justify-end items-center mb-6">
            <a href="{{ route('atencion.index') }}"
                class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Volver
            </a>
        </div>

        <!-- Tarjeta de información general -->
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-700">

            <div class="px-4 py-5 sm:px-6 border-b border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-white">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-300">
                    Detalles de la Condición de Atención
                </p>
            </div>

            <!-- Datos del Enlace -->
            <div class="border-t border-gray-700">
                <dl>

                    <!-- Campo MAC -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Cámara</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $condition->camera->name }}</dd>
                    </div>

                    <!-- Campo Marca -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Tipo de Condición de Atención</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $condition['name'] }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Feha de Inicio</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $condition['date_ini'] }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Feha de Realización</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $condition['date_end'] ?? 'Sin Fecha de Realización' }}</dd>
                    </div>

                    <!-- Campo Status -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Status</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $condition['status'] === 'Activo' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                {{ $condition['status'] }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Descripción</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $condition['description'] ?? 'Sin descripción' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('atencion.edit', $condition) }}"
                class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Editar
            </a>

            <form action="{{ route('atencion.destroy', $condition) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-red-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="return confirm('¿Estás seguro de eliminar esta Condición?')">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection
