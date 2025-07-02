@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/user/index.blade.php -->

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-gray-600  rounded-md px-3 py-1">
                Usuarios
            </h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" style="filter: opacity(60%)">
        </div>
        <br>

        <div class="flex justify-end items-center mb-6">
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

        
        {{-- valida para mostrar tabla o mensaje --}}
        @if ($persons->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-gray-800">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                    <thead class="bg-gray-700 divide-x divide-blue-400">
                        <tr class="divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Ficha</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Nombre</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Apellido</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Género</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Usuario</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Correo</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Rol</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($persons as $person)
                            <tr class="hover:bg-gray-900 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person['license'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person['name'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person['last_name'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person['sex'] }} </td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person->user->userName }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person->user->email }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $person->user->rol }}</td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
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
