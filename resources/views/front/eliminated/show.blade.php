@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/eliminated/show.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Botón Volver -->
        <div class="flex justify-end items-center mb-6">
            <a href="{{ route('eliminated.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        <!-- Tarjeta de información general -->
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-white">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-white">
                    Detalles técnicos y operativos de {{ $equipment->equipment }} seleccionado.
                </p>
            </div>

            <!-- Datos del del quipo eliminado -->
            <div class="border-t border-gray-200">
                <dl>
                    <!-- Campo id -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $equipment['id'] }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Modelo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $equipment['model'] }}</dd>
                    </div>
                    {{-- dependiendo del equipo hay campos especificos  --}}
                    @switch($equipment->equipment)
                        @case('Switch')
                            <!-- Campo numero de puertos -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">N°/Puertos</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $switch['number_ports'] }}</dd>
                            </div>
                        @break

                        @case('Nvr')
                            <!-- Campo marca -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Marca</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr['mark'] }}</dd>
                            </div>
                            <!-- Campo nombre -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr['name'] }}</dd>
                            </div>
                            <!-- Campo ip -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">IP</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr['ip'] }}</dd>
                            </div>
                            <!-- Campo numero de puertos -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">N°/Puertos</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr['ports_number'] }}</dd>
                            </div>
                            <!-- Campo numero de volumen -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">N°/Volumen</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr['slot_number'] }}</dd>
                            </div>

                            @foreach ($nvr->slotNvrDisuse as $slot)
                                <!-- Campo numero de volumen -->
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-700">Capacidad Máxima/(GB) Volumen
                                        {{ $loop->iteration }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $slot['capacity_max'] }}</dd>
                                </div>
                            @endforeach
                        @break

                        @case('Cámara')
                            <!-- Campo marca -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Marca</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['mark'] }}</dd>
                            </div>
                            <!-- Campo nombre -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['name'] }}</dd>
                            </div>
                            <!-- Campo nombre del nvr donde estaba conectada la camara -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">NVR/Conexión</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['nvr_name'] }}</dd>
                            </div>
                            <!-- Campo ip -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">IP</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['ip'] }}</dd>
                            </div>
                        @break

                        @case('Enlace')
                            <!-- Campo marca -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Marca</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['mark'] }}</dd>
                            </div>
                            <!-- Campo nombre -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['name'] }}</dd>
                            </div>
                            <!-- Campo ssid -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Ssid</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['ssid'] }}</dd>
                            </div>
                            <!-- Campo ip -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">IP</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $link['ip'] }}</dd>
                            </div>
                        @break

                        @default
                            <!-- Campo marca -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Nota de Entrega</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $camera_inventories['delivery_note'] }}</dd>
                            </div>
                            <!-- Campo nombre -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-700">Destino</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $camera_inventories['destination'] }}</dd>
                            </div>
                    @endswitch

                    <!-- Campo localidad -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"">
                        <dt class="text-sm font-medium text-gray-700">Localidad</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $equipment['location'] }}</dd>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $equipment['description'] }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        {{-- Acciones --}}
        <div class="mt-6 flex space-x-3"> <!-- Nuevo contenedor de pie -->
            <form action="{{ route('eliminated.destroy', $equipment['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection
