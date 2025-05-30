@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/switch/create.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Botón Volver -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalles del Switch</h1>
            <a href="{{ route('switch.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        <!-- Tarjeta de información general -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Detalles del Switch seleccionado.
                </p>
            </div>

            <!-- Datos del Switch -->
            <div class="border-t border-gray-200">
                <dl>
                    <!-- Campo Serial -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Serial</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $switch['serial'] }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Modelo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $switch['model'] }}</dd>
                    </div>

                    <!-- Campo Número de Puertos -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Número de Puertos</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $switch['number_ports'] }}</dd>
                    </div>

                    <!-- Campo Localidad -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Localidad</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $switch['location'] }}
                        </dd>
                    </div>

                    <!-- Campo Status -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $switch['status'] === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $switch['status'] }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $switch['description'] ?? 'Sin descripción' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('switch.edit', $switch['serial']) }}"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Editar
            </a>

            <form action="{{ route('switch.destroy', $switch['serial']) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="return confirm('¿Estás seguro de eliminar este Switch?')">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection
