@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/eliminated/index.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <!-- Encabezado  -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Historial de Equipos Eliminados</h1>
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return validateFilters()">

            {{-- Equipo --}}
            <div>
                <label class="block text-sm font-medium text-black mb-1">Tipo de Equipo</label>
                <select name="equipment"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Seleccione...</option>
                    <option value="Nvr">Nvr</option>
                    <option value="Cámara">Cámara</option>
                    <option value="Switch">Switch</option>
                    <option value="Enlace">Enlace</option>
                </select>
            </div>

            <!-- Botones: Filtrar + Limpiar -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-1 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs">
                    Filtrar
                </button>

                <!-- Botón Limpiar Filtros -->
                <a href="{{ route('eliminated.index') }}"
                    class="px-1 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-xs">
                    Limpiar
                </a>
            </div>
        </form>

        <br>

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($equipments->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Tipo de Equipo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">ID</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Fecha-Eliminación</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Localidad</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Descripción</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($equipments as $equipment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $equipment['equipment'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $equipment['id'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">
                                    {{ $equipment['created_at']->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $equipment['location'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $equipment['description'] }}</td>


                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('eliminated.show', $equipment['id']) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <form action="{{ route('eliminated.destroy', $equipment['id']) }}" method="POST"
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

            {{-- paginacion --}}
            {{ $equipments->appends([
                    'equipments' => $filters['equipments'] ?? '',
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
                const equipment = document.querySelector("select[name='equipment']")?.value.trim();

                // Verifica si está vacio
                if (!equipment) {
                    // Cancelar envío del formulario
                    alert('Por favor, ingresa al menos un valor para filtrar.');
                    return false;
                }

                // Si tiene un valor
                return true;
            }
        </script>
    @endpush

@endsection
