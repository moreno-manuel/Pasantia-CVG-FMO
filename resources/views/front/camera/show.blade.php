@extends('layouts.app-home')

@section('content')
    <!-- resources/views/front/camera/show.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalles de la Cámara</h1>

            <!-- Botón Volver -->
            <a href="{{ route('camara.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-white">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-white">
                    Detalles técnicos y operativos de la cámara.
                </p>
            </div>

            <div class="border-t border-gray-200">
                <dl>
                    <!-- Campo: Nombre -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['name'] }}</dd>
                    </div>

                    <!-- Campo: MAC -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Dirección MAC</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['mac'] }}</dd>
                    </div>

                    <!-- Campo: NVR -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">NVR ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['nvr_id'] }}</dd>
                    </div>

                    <!-- Campo: Marca -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Marca</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['mark'] }}</dd>
                    </div>

                    <!-- Campo: Modelo -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Modelo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['model'] }}</dd>
                    </div>

                    <!-- Campo: IP -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Dirección IP</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['ip'] }}</dd>
                    </div>

                    <!-- Campo: Ubicación -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $camera['location'] }}</dd>
                    </div>

                    <!-- Campo: Status -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $camera['status'] === 'Activo' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                {{ $camera['status'] }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo: Descripción -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $camera['description'] ?? 'Sin descripción' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('camara.edit', $camera['mac']) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                Editar
            </a>

            <form action="{{ route('camara.destroy', $camera['mac']) }}" method="POST"
                onsubmit="return confirm('¿Estás seguro de eliminar esta cámara?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Eliminar
                </button>
            </form>
        </div>

        {{-- valida para mostrar tabla o mensaje --}}
        @if ($conditions->isNotEmpty())
            <!-- Tabla -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Condición de atención de Cámara seleccionada</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr class="bg-gray-800 divide-x divide-blue-400">
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Condición</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Fecha-Inicio</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Fecha-Fin</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Descripción</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Status</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($conditions as $condition)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $condition->name }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $condition->date_ini }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">
                                        {{ $condition->date_end ?? 'Sin fecha de realización' }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">
                                        {{ $condition->description ?? 'Sin descripción' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $condition->status === 'Atendido' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                            {{ $condition->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm align-middle">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('atencion.show', $condition) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver
                                            </a>
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- paginacion --}}
            {{ $conditions->links() }}
        @else
            <div class="text-center mt-6 bg-gray-800 border border-black rounded-md p-4 text-white">
                <p>No hay condición de atención asociadas a la cámara seleccionada.</p>
            </div>
        @endif

    </div>
@endsection
