@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/link/show.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <!-- Botón Volver -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalles del Enlace</h1>
            <a href="{{ route('enlace.index') }}"
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
                    Detalles del enlace seleccionado.
                </p>
            </div>

            <!-- Datos del Enlace -->
            <div class="border-t border-gray-200">
                <dl>
                    <!-- Campo MAC -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Dirección MAC</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['mac'] }}</dd>
                    </div>

                    <!-- Campo Marca -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Marca</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['mark'] }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Modelo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['model'] }}</dd>
                    </div>

                    <!-- Campo Nombre -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['name'] }}</dd>
                    </div>

                    <!-- Campo SSID -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">SSID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['ssid'] }}
                        </dd>
                    </div>

                    <!-- Campo IP -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Dirección IP</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['ip'] }}</dd>
                    </div>

                    <!-- Campo Localidad -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Localidad</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['location'] }}</dd>
                    </div>

                    <!-- Campo Status -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $link['status'] === 'Activo' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                {{ $link['status'] }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $link['description'] ?? 'Sin descripción' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('enlace.edit', $link['mac']) }}"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Editar
            </a>
            <form action="{{ route('enlace.destroy', $link['mac']) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="return confirm('¿Estás seguro de eliminar este Enlace?');">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection
