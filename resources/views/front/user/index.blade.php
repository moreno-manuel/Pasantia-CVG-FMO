@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/user/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado y botón agregar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-gray-600 dark:bg-blue-900/20 rounded-md px-3 py-1">
                Usuarios
            </h1>

            <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <!-- Icono de agregar -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Nuevo Usuario
            </a>
        </div>

        <br>
        {{-- valida para mostrar tabla o mensaje --}}
        @if ($persons->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Ficha</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Nombre</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Nombre/Usuario</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Correo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Rol</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($persons as $person)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person['license'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person['name'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person->user->userName }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person->user->email }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person->user->rol }}</td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('users.show', $person->user->userName) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <a href="{{ route('users.edit', $person->user->userName) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            Editar
                                        </a>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('users.destroy', $person) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('¿Estás seguro de eliminar este usuario?')"
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
        @else
            <div class="text-center mt-6 bg-gray-800 border border-black rounded-md p-4 text-white">
                <p>No hay registros existentes</p>
            </div>
        @endif

    </div>

@endsection
