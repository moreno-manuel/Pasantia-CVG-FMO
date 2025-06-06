@extends('layouts.app-home')

@section('content')

    <!-- resources/views/front/camera/camera_inventories/show.blade.php -->

    <div class="container mx-auto px-4 py-6">

        {{-- encabezado y boton agregar --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Inventario de Cámaras</h1>
            <a href="{{ route('inventories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Agregar Nueva Cámara
            </a>
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return validateFilters()">

            <!-- Localidad -->
            <div>
                <label class="block text-sm font-medium text-black mb-1">Nota de Entrega</label>
                <input type="text" name="delivery_note" value="{{ $filters['delivery_note'] ?? '' }}"
                    class="w-full rounded-md border-black shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-black mb-1">Marca</label>
                <select name="mark"
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
                <a href="{{ route('camara.index') }}"
                    class="px-1 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-xs">
                    Limpiar
                </a>
            </div>
        </form>

        <br>

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($cameras->isNotEmpty())
            {{-- tabla --}}
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">MAC</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Marca</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Modelo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Destino/instalación</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Nota/entrega</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($cameras as $camera)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-900 truncate">{{ $camera['mac'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera['mark'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera['model'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera['destination'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera['delivery_note'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $camera['description'] }}</td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="#"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <form action="#" method="POST" class="inline">
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
            {{ $cameras->appends([
                    'delivery_note' => $filters['delivery_note'] ?? '',
                    'mark' => $filters['mark'] ?? '',
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
                const delivery_note = document.querySelector("input[name='delivery_note']")?.value.trim();
                const mark = document.querySelector("select[name='mark']")?.value.trim();

                // Verifica si estávacíos
                if (!delivery_note && !mark) {
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
