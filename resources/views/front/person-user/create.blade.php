@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/switch/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Nuevo Usuario</h1>

            <!-- Botón Volver -->
            <a href="{{ route('usuarios.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <!-- Mensaje singular o plural -->
                @if ($errors->count() === 1)
                    <strong class="font-bold">Por favor corrige el siguiente error:</strong>
                @else
                    <strong class="font-bold">Por favor corrige los siguientes errores:</strong>
                @endif

                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- formulario crear switch --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="#" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" value = '{{ old('name') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo nombre -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Apellido</label>
                        <input type="text" name="last_name" value = '{{ old('last_name') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo ficha -->
                    <div>
                        <label for="license" class="block text-sm font-medium text-gray-700">N° de Ficha</label>
                        <input type="text" name="license" value = '{{ old('license') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo género -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Género</label>
                        <select name="sex" value = '{{ old('sex') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                </div>
                <!-- Botón siguiente -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Siguiente
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
