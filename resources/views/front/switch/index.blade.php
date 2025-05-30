@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/switch/create.blade.php -->

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
                <label class="block text-sm font-medium text-gray-700 mb-1">Serial</label>
                <input type="text" name="serial" value="{{ $filters['serial'] ?? '' }}"
                    class="w-full rounded-md border-black shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <!-- ubicacion -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Localidad</label>
                <input type="text" name="location" value="{{ $filters['location'] ?? '' }}"
                    class="w-full rounded-md border-black shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
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
        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Serial</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Model</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">N°/Puertos</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Localidad</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    @foreach ($switches as $switch)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['serial'] }} </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['model'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['number_ports'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['location'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $switch['status'] === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $switch['status'] }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4 text-sm space-x-2">
                                <div class="flex space-x-2">
                                    <a href="{{ route('switch.show', $switch) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Ver
                                    </a>
                                    <a href="{{ route('switch.edit', $switch) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        Editar
                                    </a>
                                    <form action="{{ route('switch.destroy', $switch) }}" method="POST" class="inline">
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
        {{ $switches->appends([
                'serial' => $filters['serial'] ?? '',
                'location' => $filters['location'] ?? '',
                'status' => $filters['status'] ?? '',
            ])->links() }}


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
    </div>
@endsection
