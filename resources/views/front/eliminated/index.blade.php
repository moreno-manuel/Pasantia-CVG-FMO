@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/eliminated/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado  -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1">Equipos Eliminados</h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" style="filter: opacity(60%)">
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return validateFilters('eliminated')">

            <!-- tipo de condición -->
            <div>
                <label for="equipment" class="block text-sm font-medium text-black">Tipo de Equipo</label>
                <select name="equipment" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                    <option value="">Seleccione..</option>
                    <option value="Nvr" {{ ($filters['equipment'] ?? '') == 'Nvr' ? 'selected' : '' }}>
                        Nvr</option>
                    <option value="Cámara" {{ ($filters['equipment'] ?? '') == 'Cámara' ? 'selected' : '' }}>Cámara
                    </option>
                    <option value="Enlace" {{ ($filters['equipment'] ?? '') == 'Enlace' ? 'selected' : '' }}>Enlace
                    </option>
                    <option value="Switch" {{ ($filters['equipment'] ?? '') == 'Switch' ? 'selected' : '' }}>Switch</option>
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
                <a href="{{ route('eliminated.index') }}"
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
        @if ($equipments->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto rounded-lg shadow border border-zinc-500 bg-zinc-700">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                    <thead class="bg-red-900 divide-x divide-white">
                        <tr class="divide-x divide-white">
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
                            <tr class="hover:bg-zinc-800 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $equipment['equipment'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $equipment['id'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">
                                    {{ $equipment['created_at']->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $equipment['location'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $equipment['description'] }}</td>


                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('eliminated.show', $equipment['id']) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        @if (auth()->user()->rol == 'admin')
                                            <form action="{{ route('eliminated.destroy', $equipment['id']) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
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
            <div class="text-center mt-6 bg-red-900 border border-black rounded-md p-4 text-white">
                <p>No hay registros existentes</p>
            </div>
        @endif

    </div>
@endsection
