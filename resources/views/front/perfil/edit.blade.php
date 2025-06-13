@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/perfil/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título  --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Datos de Usuario</h1>
            </div>

            {{-- Formulario --}}
            <form action="#" method="POST">
                @csrf
                @method('PUT')

                {{-- datos persona --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo ficha  -->
                    <div>
                        <label for="ficha" class="block text-sm font-semibold text-white">Ficha</label>
                        <input type="text" name="ficha" value="{{ old('ficha', $user->person->license) }}"
                            class="mt-1 block w-full rounded-md bg-gray-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>


                    <!-- Campo nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ old('name', $user->person->name) }}" readonly>
                    </div>

                    <!-- Campo apellido  -->
                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-white">Apellido</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->person->last_name) }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo genero -->
                    <div>
                        <label for="sex" class="block text-sm font-semibold text-white">Género</label>
                        <select name="sex" id="sex"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            <option value="Masculino" {{ old('sex', $user->person->sex) == 'Masculino' ? 'selected' : '' }}>
                                Masculino
                            </option>
                            <option value="Femenino" {{ old('sex', $user->person->sex) == 'Femenino' ? 'selected' : '' }}>
                                Femenino
                            </option>
                        </select>
                    </div>
                </div>

                <br>
                {{-- datos usuario --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo nombre de usuario  -->
                    <div>
                        <label for="ficha" class="block text-sm font-semibold text-white">Nombre de Usuario</label>
                        <input type="text" name="ficha" value="{{ old('ficha', $user->userName) }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-white">Email</label>
                        <input type="text" name="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>
                
                    <div>
                        <label for="password" class="block text-sm font-semibold text-white">Contraseña</label>
                        <input id="password" type="password"
                            class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-500 @enderror"
                            name="password" value="{{ old('password', $user->password) }}" required
                            autocomplete="current-password">

                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Botón Actualizar -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-end px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            onclick="return confirm('¿Está seguro de actualizar datos de usuario?')">
                            Actualizar Datos
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
