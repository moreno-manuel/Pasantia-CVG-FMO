@extends('layouts.app-home')

@section('content')

    <!-- resources/views/front/stock/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1">Equipos en Stock</h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" class="h-14" style="filter: opacity(60%)">
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" onsubmit="return validateFilters('stock')">

            <!-- Campo eq -->
            <div>
                <label for="equipment" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Equipo</label>
                <select name="equipment" id="equipment"
                    class="w-full rounded-md bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Seleccione..</option>
                    @foreach ($equipments as $equipment)
                        <option value="{{ $equipment }}" {{ old('equipment') == $equipment ? 'selected' : '' }}>
                            {{ $equipment }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Campo Modelo -->
            <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                <input type="text" name="model" value="{{ $filters['model'] ?? '' }}"
                    class="w-full rounded-md bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                    placeholder="Buscar por modelo">
            </div>

            <!-- Campo Nota de Entrega -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nota de Entrega</label>
                <input type="text" name="delivery_note" value="{{ $filters['delivery_note'] ?? '' }}"
                    class="w-full rounded-md bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                    placeholder="Buscar por nota de entrega">
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
                <a href="{{ route('stock.index') }}"
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

        {{-- boton para crear nuevo registro --}}
        @if (auth()->user()->rol != 'lector')
            <div class="flex justify-end items-center mb-6">

                <a href="{{ route('stock.create') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <!-- Icono de agregar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Nueva Equipo
                </a>
            </div>
        @endif

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($eqs->isNotEmpty())
            {{-- tabla --}}
            <div class="overflow-x-auto rounded-lg shadow border border-zinc-500 bg-zinc-700">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                    <thead class="bg-red-900 divide-x divide-white">
                        <tr class="divide-x divide-white">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Equipo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">MAC</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Marca</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Modelo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Nota/entrega</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Descripción</th>
                            @if (auth()->user()->rol != 'lector')
                                <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Acción</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($eqs as $eq)
                            <tr class="hover:bg-zinc-800 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $eq['equipment'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $eq['mac'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $eq['mark'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $eq['model'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $eq['delivery_note'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">
                                    {{ $eq['description'] ?? 'Sin descripción' }}</td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">

                                        @if (auth()->user()->rol != 'lector')
                                            <a href="{{ route('stock.edit', $eq->mac) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                Editar
                                            </a>
                                            @if (auth()->user()->rol == 'admin')
                                                <!-- Botón Eliminar -->
                                                <button type="button"
                                                    onclick="openDeleteModal('{{ route('stock.destroy', $eq->mac) }}')"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Eliminar
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- paginacion --}}
            {{ $eqs->appends([
                    'delivery_note' => $filters['delivery_note'] ?? '',
                    'model' => $filters['model'] ?? '',
                    'equipment' => $filters['equipment'] ?? '',
                ])->links() }}
        @else
            <div class="text-center mt-6 bg-red-900 border border-black rounded-md p-4 text-white">
                <p>No hay registros existentes</p>
            </div>
        @endif

    </div>


    <!-- Modal para confirmar eliminación con descripción -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black-opaco">
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
@endsection
