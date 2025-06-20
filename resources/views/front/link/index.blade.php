@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/link/index.blade.php -->


    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado y botón agregar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-gray-600 rounded-md px-3 py-1">Enlaces</h1>

            <a href="{{ route('enlace.create') }}"
                class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <!-- Icono de agregar -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Nuevo Enlace
            </a>
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" onsubmit="return validateFilters()">

            <!-- Localidad -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Localidad</label>
                <input type="text" name="location" value="{{ $filters['location'] ?? '' }}"
                    class="w-full rounded-md bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                    placeholder="Buscar por localidad">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="w-full rounded-md bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos</option>
                    <option value="Activo" {{ ($filters['status'] ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ ($filters['status'] ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo
                    </option>
                </select>
            </div>

            <!-- Botones: Filtrar + Limpiar -->
            <div class="flex items-end space-x-2">
                <!-- Botón Filtrar -->
                <button type="submit"
                    class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <!-- Icono de buscar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Buscar
                </button>

                <!-- Botón Limpiar -->
                <a href="{{ route('inventories.index') }}"
                    class="inline-flex items-center px-2 py-1 bg-gray-500 text-white text-xs font-semibold uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <!-- Icono de limpiar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Limpiar
                </a>
            </div>
        </form>

        <br>

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($links->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-gray-800">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                    <thead class="bg-gray-700 divide-x divide-blue-400">
                        <tr class="divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">MAC</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Marca</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Modelo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Nombre</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Localidad</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">IP</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($links as $link)
                            <tr class="hover:bg-gray-900 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white truncate">{{ $link['mac'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $link['mark'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $link['model'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $link['name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $link['location'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $link['ip'] }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $link['status'] === 'Activo' ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                        {{ $link['status'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('enlace.show', $link['name']) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <a href="{{ route('enlace.edit', $link['name']) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            Editar
                                        </a>
                                        <!-- Botón Eliminar -->
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('enlace.destroy', $link['mac']) }}')"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- paginacion --}}
            {{ $links->appends([
                    'location' => $filters['location'] ?? '',
                    'status' => $filters['status'] ?? '',
                ])->links() }}
        @else
            <div class="text-center mt-6 bg-gray-800 border border-black rounded-md p-4 text-white">
                <p>No hay registros existentes</p>
            </div>
        @endif

    </div>

    {{-- funcion para los filtros --}}
    @push('scripts')
        <script>
            function validateFilters() {
                // Obtén los valores de los campos de filtro
                const location = document.querySelector("input[name='location']")?.value.trim();
                const status = document.querySelector("select[name='status']")?.value.trim();

                // Verifica si estávacíos
                if (!location && !status) {
                    // Cancelar envío del formulario
                    alert('Por favor, ingresa al menos un valor para filtrar.');
                    return false;
                }
                // Si hay un valor
                return true;
            }
        </script>
    @endpush

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
