@extends('layouts.app-home')
@section('content')
    <!-- resources/views/switch/index.blade.php -->

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
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <!-- Modelo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                <input type="text" name="model" value="{{ $filters['model'] ?? '' }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>

            <!-- Número de Puertos -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Puertos</label>
                <input type="number" name="number_ports" value="{{ $filters['number_ports'] ?? '' }}" min="1"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>

            <!-- Botones: Filtrar + Limpiar -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Filtrar
                </button>

                <!-- Botón Limpiar Filtros -->
                <a href="{{ route('switch.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-sm">
                    Limpiar
                </a>
            </div>
        </form>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-36">Serial</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Modelo</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-28">Puertos</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-40">Persona Usuario</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    @foreach ($switches as $switch)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['serial'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['model'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['number_ports'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $switch['user_person'] }}</td>
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
                'model' => $filters['model'] ?? '',
                'number_ports' => $filters['number_ports'] ?? '',
            ])->links() }}

        {{-- funcion para los filtros --}}
        @push('scripts')
            <script>
                function validateFilters() {
                    // Obtén los valores de los campos de filtro
                    const serial = document.querySelector("input[name='serial']")?.value.trim();
                    const model = document.querySelector("input[name='model']")?.value.trim();
                    const numberPorts = document.querySelector("input[name='number_ports']")?.value.trim();

                    // Verifica si todos están vacíos
                    if (!serial && !model && (!numberPorts || parseInt(numberPorts) <= 0)) {
                        // Cancelar envío del formulario
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }

                    // Si al menos uno tiene valor, permite el envío
                    return true;
                }
            </script>
        @endpush
    @endsection
