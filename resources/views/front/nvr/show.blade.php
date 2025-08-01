@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/show.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <div class="flex justify-end items-center mb-6">

            <!-- Botón Volver -->
            <a href="{{ route('nvr.index') }}"
                class="inline-flex items-center px-3 py-1.5 bg-zinc-600 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-zinc-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
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
                    Detalles técnicos y operativos del NVR seleccionado.
                </p>
            </div>

            <!-- Datos del NVR -->
            <div class="border-t border-gray-700">
                <dl>

                    <!-- Campo MAC -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Dirección MAC</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->mac }}</dd>
                    </div>

                    <!-- Campo Nombre -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Nombre</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->name }}</dd>
                    </div>

                    <!-- Campo Marca -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Marca</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->mark }}</dd>
                    </div>

                    <!-- Campo Modelo -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Modelo</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->model }}</dd>
                    </div>

                    <!-- Campo IP -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Dirección IP</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->ip }}</dd>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">N° Puertos</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->ports_number }}</dd>
                    </div>

                    <!-- Campo Puertos Usados -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">N° Puertos/Usados</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->camera()->count() }}</dd>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">N° Puertos/Disponibles</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $nvr->ports_number - $nvr->camera()->count() }}
                        </dd>
                    </div>

                    <!-- Campo Ranuras de Disco -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">N° Volumen</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->slot_number }}</dd>
                    </div>

                    <!-- Campo Localidad -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Localidad</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $nvr->location }}</dd>
                    </div>

                    <!-- Campo Status -->
                    <div class="bg-zinc-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Status</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if ($nvr->status == 'online') bg-green-600 text-green-100
                                @elseif($nvr->status == 'offline') bg-red-600 text-red-100
                                @else bg-yellow-600 text-yellow-100 @endif">
                                {{ $nvr->status }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="bg-zinc-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Descripción</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $nvr->description ?? 'Sin descripción' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tabla de Volumenes-->
        <div class="mt-8">
            <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-zinc-600">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-white">
                    <thead class="bg-zinc-700 divide-x divide-white">
                        <tr class="divide-x divide-white">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Volumen</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Serial</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Capacidad/Disco (TB)</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Capacidad Máxima/volumen (TB)
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($nvr->slotNvr as $index => $slot)
                            <tr class="hover:bg-zinc-600 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $slot->hdd_serial }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $slot->hdd_capacity }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $slot->capacity_max }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $slot->status === 'Disponible' ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                        {{ $slot->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if (auth()->user()->rol != 'lector')
            <!-- Acciones -->
            <div class="mt-6 flex space-x-3">
                <a href="{{ route('nvr.edit', $nvr->name) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Editar
                </a>

                @if ($nvr->camera()->count() > 0)
                    {{-- si el nvr tiene cámaras conectadas no se puede eliminar --}}
                    <!-- Botón Eliminar -->
                    <button type="button" onclick="submit()"
                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-red-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Eliminar
                    </button>
                @else
                    @if (auth()->user()->rol == 'admin')
                        <!-- Botón Eliminar -->
                        <button type="button" onclick="openDeleteModal('{{ route('nvr.destroy', $nvr->mac) }}')"
                            class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-red-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Eliminar
                        </button>
                    @endif
                @endif
            </div>
        @endif


        <br>
        <br>

        <div class="flex justify-left items-center mb-6">
            <h1 class="font-bold text-white bg-zinc-600 rounded-md px-3 py-1">Cámaras Conectadas al NVR seleccionado</h1>
        </div>

        {{-- tabla de cámaras conectadas al nvr --}}
        @if ($cameras->isNotEmpty())
            <div class="mt-8">
                <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-zinc-600">
                    <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                        <thead class="bg-red-900 divide-x divide-blue-400">
                            <tr class="divide-x divide-white">
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Mac</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Nombre</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Localidad</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">IP</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Status</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($cameras as $camera)
                                <tr class="hover:bg-zinc-800 transition-colors duration-150">
                                    <td class="px-6 py-4 text-center text-sm text-white">{{ $camera->mac }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-white">{{ $camera->name }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-white">{{ $camera->location }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-white">{{ $camera->ip }}</td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if ($camera->status == 'online') bg-green-600 text-green-100
                                                    @elseif($camera->status == 'offline') bg-red-600 text-red-100
                                                    @else bg-yellow-600 text-yellow-100 @endif">
                                            {{ $camera->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm align-middle">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('camara.show', $camera['name']) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver
                                            </a>

                                            @if (auth()->user()->rol != 'lector')
                                                <a href="{{ route('camara.edit', $camera['name']) }}"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                    Editar
                                                </a>

                                                @if (auth()->user()->rol == 'admin')
                                                    <button type="button"
                                                        onclick="openDeleteModal('{{ route('camara.destroy', $camera['mac']) }}')"
                                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        Eliminar
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- paginacion --}}
            {{ $cameras->links() }}
        @else
            <div class="text-center mt-6 bg-red-900 border border-black rounded-md p-4 text-white">
                <p>No hay cámaras conectadas al Nvr</p>
            </div>
        @endif

    </div>

    <!-- Modal para confirmar eliminación con descripción -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black-opaco">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Confirmar Eliminación</h3>
            <p>¿Estás seguro de que deseas eliminar este Enlace?</p>

            <label for="reason" class="block mt-4 mb-2 font-medium">Motivo de eliminación:</label>
            <textarea id="reason" name="reason" rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                placeholder="Describa el motivo..."></textarea>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-zinc-600 text-white rounded hover:bg-zinc-700">Cancelar</button>
                <button type="button" onclick="submitDeleteForm()"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="deletion_description" id="deletionReasonInput">
    </form>

@endsection
