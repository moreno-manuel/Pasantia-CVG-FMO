@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/user/show.blade.php -->
    <div class="container mx-auto px-4 py-6">
        <!-- Botón Volver -->
        <div class="flex justify-end items-center mb-6">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Volver
            </a>
            </a>
        </div>

        <!-- Tarjeta de información del usuario -->
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-700">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-white">
                    Información General
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-300">
                    Datos del usuario seleccionado.
                </p>
            </div>

            <!-- Datos del Usuario -->
            <div class="border-t border-gray-700">
                <dl>

                    <!-- Campo Persona Asociada -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Nombre</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $person->name }} {{ $person->last_name }}
                        </dd>
                    </div>

                    {{-- campo Ficha --}}
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Ficha</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $person->license }}
                        </dd>
                    </div>

                    {{-- campo sex --}}
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Género</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $person->sex === 'Masculino' ? 'bg-indigo-900 text-indigo-200' : 'bg-pink-900 text-pink-200' }}">
                                {{ $person->sex }}
                            </span>
                        </dd>
                    </div>

                    <!-- Campo Nombre de Usuario -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Nombre de Usuario</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $person->user->userName }}
                        </dd>
                    </div>

                    <!-- Campo Correo Electrónico -->
                    <div class="bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Correo Electrónico</dt>
                        <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                            {{ $person->user->email }}
                        </dd>
                    </div>

                    <!-- Campo Rol -->
                    <div class="bg-gray-600 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-300">Rol</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $person->user->rol }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('users.edit', $person->user->userName) }}"
                class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Editar
            </a>

            <form action="{{ route('users.destroy', $person) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-red-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection
