@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/show.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalles del NVR</h1>

            <!-- Botón Volver -->
            <a href="{{ route('nvr.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        <!-- Tarjeta de información general -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Información del NVR
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Detalles del NVR seleccionado.
                </p>
            </div>

            <!-- Datos del NVR -->
            <div class="border-t border-gray-200">
                <dl>
                    <!-- Campo MAC -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Dirección MAC</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->mac }}</dd>
                    </div>

                    <!-- Campo Nombre -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->name }}</dd>
                    </div>

                    <!-- Campo Marca -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Marca</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->mark }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Modelo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->model }}</dd>
                    </div>

                    <!-- Campo IP -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Dirección IP</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->ip }}</dd>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">N° Puertos</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->ports_number }}</dd>
                    </div>

                    {{-- Para calcular el N° de puertos usados/disponibles --}}
                    @php
                        $ports_use = $nvr->camera;
                    @endphp
                    <!-- Campo Puertos Usados -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">N° Puertos/Usados</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $ports_use->count() }}</dd>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">N° Puertos/Disponibles</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $nvr->ports_number - $ports_use->count() }}</dd>
                    </div>

                    <!-- Campo Ranuras de Disco -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">N° Volumen</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->slot_number }}</dd>
                    </div>

                    <!-- Campo Localidad -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Localidad</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $nvr->location }}</dd>
                    </div>

                    <!-- Campo Estado -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Status</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $nvr->status === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $nvr->status }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-700">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $nvr->description ?? 'Sin descripción' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tabla de Volumenes-->

        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-700 mb-3">Detalles Volumen</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Volumen</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Serial</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Capacidad/Disco (GB)</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Capacidad Máxima/volumen (GB)
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($nvr->slotNvr as $index => $slot)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $i }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $slot->hdd_serial }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $slot->hdd_capacity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $slot->capacity_max }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $slot->status === 'Ocupado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $slot->status === 'Ocupado' ? 'Disponible' : 'Ocupado' }}
                                    </span>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones del NVR -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('nvr.edit', $nvr) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150">
                Editar NVR
            </a>

            <form action="{{ route('nvr.destroy', $nvr) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                    onclick="return confirm('¿Estás seguro de eliminar este NVR?')">
                    Eliminar NVR
                </button>
            </form>
        </div>


        @if ($nvr->camera->isNotEmpty())
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Cámaras Conectadas al Nvr</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Mac</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ubicación</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">IP</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($nvr->camera as $camera)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $camera->mac }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $camera->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $camera->location }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $camera->ip }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $camera->status === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $camera->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <div class="flex space-x-2">
                                            <a href="#"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver
                                            </a>
                                            <a href="#"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                Editar
                                            </a>
                                            <form action="#" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="mt-6 bg-gray-100 border border-gray-300 rounded-md p-4 text-gray-700">
                <p>No hay cámaras asociadas a este NVR.</p>
            </div>
        @endif


    </div>
@endsection
