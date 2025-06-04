@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado y botón agregar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">NVR</h1>
            <a href="{{ route('nvr.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Agregar Nuevo NVR
            </a>
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return validateFilters()">

            <!-- Localidad -->
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
                <a href="{{ route('nvr.index') }}"
                    class="px-1 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-xs">
                    Limpiar
                </a>
            </div>
        </form>

        <br>

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($nvrs->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">MAC</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">Marca</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">Modelo</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">Nombre</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">N°/Puertos</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">Localidad</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">IP</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($nvrs as $nvr)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">{{ $nvr['mac'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $nvr['mark'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $nvr['model'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $nvr['name'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $nvr['ports_number'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $nvr['location'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $nvr['ip'] }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $nvr['status'] === 'Activo' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                        {{ $nvr['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex space-x-2">
                                        <!-- Botón Ver -->
                                        <a href="{{ route('nvr.show', $nvr) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <!-- Botón Editar -->
                                        <a href="{{ route('nvr.edit', $nvr) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            Editar
                                        </a>
                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('nvr.destroy', $nvr) }}" method="POST" class="inline">
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

            {{-- paginacion --}}
            {{ $nvrs->appends([
                    'location' => $filters['location'] ?? '',
                    'status' => $filters['status'] ?? '',
                ])->links() }}
        @else
            <div class="text-center mt-6 bg-gray-100 border border-gray-300 rounded-md p-4 text-white">
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

@endsection
