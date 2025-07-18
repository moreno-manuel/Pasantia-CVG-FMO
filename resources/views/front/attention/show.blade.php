@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/attention/show.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Botón Volver -->
        <div class="flex justify-end items-center mb-6">
            <button type="button" onclick="window.history.back()"
                class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Volver
            </button>
        </div>

        <!-- Tarjeta de información general -->
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-700">

            <div class="px-4 py-5 sm:px-6 border-b border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-white">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-300">
                    Detalles de la Condición de Atención
                </p>
            </div>

            <!-- Datos de la condicion -->
            <div class="border-t border-gray-700">
                <dl>

                    <!-- Campo Nvr -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Nvr/Conexión</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $condition->camera->nvr->name }}</dd>
                    </div>

                    <!-- Campo camara -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Cámara</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $condition->camera->name }}</dd>
                    </div>

                    <!-- Campo nombre condicion -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Tipo de Condición</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $condition['other_name'] ?? $condition['name'] }}</dd>
                    </div>

                    <!-- Campo fecha ini -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Feha de Inicio</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">{{ $condition['date_ini'] }}</dd>
                    </div>

                    <!-- Campo fecha fin -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Feha de Realización</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $condition['date_end'] ?? 'Sin Fecha de Realización' }}</dd>
                    </div>

                    <!-- campo descrpcion-->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Descripción</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $condition['description'] ?? 'Sin Fecha de Realización' }}</dd>
                    </div>

                    <!-- Campo Status -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Status</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $condition['status'] === 'Atendido' ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                {{ $condition['status'] }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            @if (auth()->user()->rol != 'lector')
                <!-- Acciones -->
                @if (!$condition->date_end)
                    <a href="{{ route('atencion.edit', $condition) }}"
                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Editar
                    </a>
                @endif

                <form action="{{ route('atencion.destroy', $condition) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-red-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        onclick="return confirm('¿Estás seguro de eliminar esta Condición?')">
                        Eliminar
                    </button>
                </form>
        </div>
        @endif

        <br>
        <br>


        {{-- tabla de control de la condicion --}}
        <div class="flex justify-left items-center mb-6">
            <h1 class="font-bold text-white bg-gray-800 rounded-md px-3 py-1">Control de la condición</h1>
        </div>
        @if ($condition->controlCondition->isNotEmpty())
            <!-- Tabla -->
            <div class="mt-8">

                <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-gray-800">
                    <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                        <thead class="bg-gray-700 divide-x divide-blue-400">
                            <tr class="divide-x divide-blue-400">
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Descripción</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Fecha</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-white">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($controlConditions as $controlCondition)
                                <tr class="hover:bg-gray-900 transition-colors duration-150">
                                    <td class="px-6 py-4 text-center text-sm text-white">{{ $controlCondition->text }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-white">
                                        {{ $controlCondition->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-white">
                                        {{ $controlCondition->created_at->format('H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{--  paginacion --}}
            {{ $controlConditions->links() }}
        @else
            <div class="text-center mt-6 bg-gray-800 border border-black rounded-md p-4 text-white">
                <p>No se han agregado registros.</p>
            </div>
        @endif
    </div>
@endsection
