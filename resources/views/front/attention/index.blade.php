@extends('layouts.app-home')
@section('content')

    <!-- resources/views/front/condition/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1">Condicion de Atención</h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" class="h-14" style="filter: opacity(60%)">
        </div>


        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" onsubmit="return validateFilters('atencion')">

            <!-- tipo de condición -->
            <div>
                <label for="name" class="block text-sm font-medium text-black">Tipo de condición</label>
                <select name="name" id="name"
                    class="mt-1 block w-full rounded-md bg-white border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    required>
                    <option value="">Seleccione..</option>
                    @foreach ($names as $name)
                        <option value="{{ $name }}" {{ ($filters['name'] ?? '') == $name ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach

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
                <a href="{{ route('atencion.index') }}"
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

        {{-- botonn agregar nueva condicion --}}
        @if (auth()->user()->rol != 'lector')
            <div class="flex justify-end items-center mb-6">

                <a href="{{ route('atencion.create') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <!-- Icono de agregar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Nueva Condición
                </a>
            </div>
        @endif

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($conditions->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto rounded-lg shadow border border-zinc-500 bg-zinc-700">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                    <thead class="bg-red-900 divide-x divide-white">
                        <tr class="divide-x divide-white">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Nvr/Conexión</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Cámara</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Tipo de Condición</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Fecha-Inicio</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Descripción</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($conditions as $condition)
                            <tr class="hover:bg-zinc-800 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $condition->camera->nvr->name }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $condition->camera->name }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">
                                    {{ $condition['other_name'] ?? $condition['name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $condition['date_ini'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">
                                    {{ $condition['description'] }}</td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('atencion.show', $condition) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>

                                        @if (auth()->user()->rol != 'lector')
                                            <a href="{{ route('atencion.edit', $condition) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                Editar
                                            </a>
                                            <form action="{{ route('atencion.destroy', $condition) }}" method="POST"
                                                class="inline">
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
            {{ $conditions->appends([
                    'name' => $filters['name'] ?? '',
                ])->links() }}
        @else
            <div class="text-center mt-6 bg-red-900 border border-black rounded-md p-4 text-white">
                <p>No hay Condición de Atención por atender</p>
            </div>
        @endif

    </div>
@endsection
