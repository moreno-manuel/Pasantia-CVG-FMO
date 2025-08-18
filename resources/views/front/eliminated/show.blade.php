@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/eliminated/show.blade.php -->
    <div class="container mx-auto px-4 py-6">
        <!-- Botón Volver -->
        <div class="flex justify-end items-center mb-6">
            <a href="{{ route('eliminated.index') }}"
                class="inline-flex items-center px-3 py-1.5 bg-zinc-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-zinc-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Volver
            </a>
        </div>

        <!-- Tarjeta de información general -->
        <div class="bg-red-900 shadow overflow-hidden sm:rounded-lg border border-gray-700">

            <div class="px-4 py-5 sm:px-6 border-b border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-white">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-300">
                    Detalles técnicos y operativos de {{ $equipment->equipment }} seleccionado.
                </p>
            </div>

            <!-- Datos del equipo eliminado -->
            <div class="border-t border-gray-700">
                <dl>
                    <!-- Campo ID -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-semibold text-gray-300">ID</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $equipment['id'] }}</dd>
                    </div>

                    <!-- Marca -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-semibold text-gray-300">Marca</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $equipment['mark'] }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-semibold text-gray-300">Modelo</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $equipment['model'] }}</dd>
                    </div>

                    {{-- Mostrar campos específicos según el tipo de equipo --}}
                    @switch($equipment_type)
                        @case('Switch')
                            <!-- Switch - Número de puertos -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">N° Puertos</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $switch['number_ports'] ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Campo Localidad -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Localidad</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['location'] ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Campo Descripción -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Descripción</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['description'] }}
                                </dd>
                            </div>
                        @break

                        @case('NVR')
                            <!-- NVR - Nombre -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Nombre</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr['name'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- NVR - IP -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Dirección IP</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr['ip'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- NVR - Número de puertos -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">N° de Puertos</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr['ports_number'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- NVR - Número de volumen -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">N° de Volumen</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr['slot_number'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- NVR - Capacidad de cada volumen -->
                            @foreach ($nvr->slotNvrDisuse as $slot)
                                <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-semibold text-gray-300"> Vol
                                        {{ $loop->iteration }} - Capacidad/Max.(TB)</dt>
                                    <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $slot['capacity_max'] ?? 'N/A' }}
                                    </dd>
                                </div>
                            @endforeach

                            <!-- Campo Localidad -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Localidad</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['location'] ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Campo Descripción -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Descripción</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['description'] }}
                                </dd>
                            </div>
                        @break

                        @case('Cámara')
                            <!-- Cámara - Nombre -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Nombre</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $camera['name'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- Cámara - NVR donde estaba conectada -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">NVR/Conexión (MAC - nombre)</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $camera['nvr'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- Cámara - Dirección IP -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">IP</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $camera['ip'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- Campo Localidad -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Localidad</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['location'] ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Campo Descripción -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Descripción</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['description'] }}
                                </dd>
                            </div>
                        @break

                        @case('Enlace')
                            <!-- Enlace - Nombre -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Nombre</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $link['name'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- Enlace - SSID -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">SSID</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $link['ssid'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- Enlace - Dirección IP -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">IP</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $link['ip'] ?? 'N/A' }}</dd>
                            </div>

                            <!-- Campo Localidad -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Localidad</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['location'] ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Campo Descripción -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Descripción</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['description'] }}
                                </dd>
                            </div>
                        @break

                        @default
                            <!-- Por defecto: equipo en stock -->
                            <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Nota de Entrega</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $stock['delivery_note'] ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Campo Descripción -->
                            <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-semibold text-gray-300">Descripción</dt>
                                <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                                    {{ $equipment['description'] }}
                                </dd>
                            </div>
                    @endswitch
                </dl>
            </div>
        </div>

        @if (auth()->user()->rol == 'admin')
            <!-- Acciones -->
            <div class="mt-6 flex justify-end space-x-3">
                <form action="{{ route('eliminated.destroy', $equipment['id']) }}" method="POST"
                    onsubmit="return confirm('¿Estás seguro de eliminar este equipo permanentemente?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-red-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Eliminar Permanentemente
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
