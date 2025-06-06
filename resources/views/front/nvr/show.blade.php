@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/show.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold"></h1>

            <!-- Botón Volver -->
            <a href="{{ route('nvr.index') }}"
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
                    Detalles técnicos y operativos del NVR seleccionado.
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
                                {{ $nvr->status === 'Activo' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
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
            <h3 class="text-lg font-medium text-black mb-3">Detalles Volumen</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Volumen</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Serial</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Capacidad/Disco (GB)</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Capacidad Máxima/volumen (GB)
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($nvr->slotNvr as $index => $slot)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $slot->hdd_serial }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $slot->hdd_capacity }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $slot->capacity_max }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $slot->status === 'Disponible' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                        {{ $slot->status }}
                                    </span>
                                </td>
                            </tr>
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

            <!-- Botón Eliminar -->
            <button type="button" onclick="openDeleteModal('{{ route('nvr.destroy', $nvr) }}')"
                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Eliminar
            </button>
        </div>

        <br>
        @if ($cameras->isNotEmpty())
            <div class="mt-8">
                <h3 class="text-lg font-medium text-black mb-3">Cámaras Conectadas al Nvr</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr class="bg-gray-800 divide-x divide-blue-400">
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera->mac }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera->name }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera->location }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera->ip }}</td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $camera->status === 'Activo' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                            {{ $camera->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm align-middle">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('camara.show', $camera['mac']) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver
                                            </a>
                                            <a href="{{ route('camara.edit', $camera['mac']) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                Editar
                                            </a>
                                            <form action="{{ route('camara.destroy', $camera['mac']) }}" method="POST"
                                                class="inline">
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
            {{-- paginacion --}}
            {{ $cameras->links() }}
        @else
            <div class="text-center mt-6 bg-gray-800 border border-black rounded-md p-4 text-white">
                <p>No hay cámaras conectadas al Nvr seleccionado</p>
            </div>
        @endif

    </div>

    <!-- Modal para confirmar eliminación con descripción -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Confirmar Eliminación</h3>
            <p>¿Estás seguro de que deseas eliminar este Enlace?</p>

            <label for="reason" class="block mt-4 mb-2 font-medium">Motivo de eliminación:</label>
            <textarea id="reason" name="reason" rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                placeholder="Describa el motivo..."></textarea>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancelar</button>
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

    {{-- funcion para obtener la descripcion --}}
    @push('scripts')
        <script>
            let deleteUrl = '';

            function openDeleteModal(url) {
                deleteUrl = url;
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('reason').value = '';
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            function submitDeleteForm() {
                const reason = document.getElementById('reason').value.trim();

                if (!reason) {
                    alert("Por favor, describa un motivo para eliminar.");
                    return;
                }

                const form = document.getElementById('deleteForm');
                form.action = deleteUrl;
                document.getElementById('deletionReasonInput').value = reason;
                form.submit();
            }
        </script>
    @endpush

@endsection
