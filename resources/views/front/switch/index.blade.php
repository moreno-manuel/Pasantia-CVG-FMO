@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/switch/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado y botón agregar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Switches</h1>
            <a href="{{ route('switch.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Agregar Nuevo Switch
            </a>
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return validateFilters()">

            <!-- Serial -->
            <div>
                <label class="block text-sm font-medium text-black mb-1">Serial</label>
                <input type="text" name="serial" value="{{ $filters['serial'] ?? '' }}" 
                    class="w-full rounded-md border-black shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <!-- ubicacion -->
            <div>
                <label class="block text-sm font-medium text-black mb-1">Localidad</label>
                <input type="text" name="location" value="{{ $filters['location'] ?? '' }}"
                    class="w-full rounded-md border-black shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-black mb-1">Status</label>
                <select name="status"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>

            <!-- Botones: Filtrar + Limpiar -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-1 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs">
                    Filtrar
                </button>

                <!-- Botón Limpiar Filtros -->
                <a href="{{ route('switch.index') }}"
                    class="px-1 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-xs">
                    Limpiar
                </a>
            </div>
        </form>

        <br>

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($switches->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Serial</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Model</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">N°/Puertos</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Localidad</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($switches as $switch)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $switch['serial'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $switch['model'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $switch['number_ports'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $switch['location'] }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $switch['status'] === 'Activo' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                        {{ $switch['status'] }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('switch.show', $switch) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <a href="{{ route('switch.edit', $switch) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            Editar
                                        </a>
                                        <!-- Botón Eliminar -->
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('switch.destroy', $switch) }}')"
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
            {{ $switches->appends([
                    'serial' => $filters['serial'] ?? '',
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
                const serial = document.querySelector("input[name='serial']")?.value.trim();
                const location = document.querySelector("input[name='location']")?.value.trim();
                const status = document.querySelector("select[name='status']")?.value.trim();

                // Verifica si está vacio
                if (!serial && !location && !status) {
                    // Cancelar envío del formulario
                    alert('Por favor, ingresa al menos un valor para filtrar.');
                    return false;
                }

                // Si tiene un valor
                return true;
            }
        </script>
    @endpush

    <!-- Modal para confirmar eliminación con descripción -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Confirmar Eliminación</h3>
            <p>¿Estás seguro de que deseas eliminar este switch?</p>

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
