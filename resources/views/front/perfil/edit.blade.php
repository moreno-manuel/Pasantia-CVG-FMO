@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/perfil/edit.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-zinc-600 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título  --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Datos de Usuario</h1>

                <!-- Botón preguntas de seguridad -->
                <a href="{{ route('security.showForm') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Preguntas de Seguridad
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('perfil.update', $user->userName) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- datos persona --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo ficha  -->
                    <div>
                        <label for="ficha" class="block text-sm font-semibold text-white">Ficha</label>
                        <input type="text" name="ficha" value="{{ $user->person->license }}"
                            class="mt-1 block w-full rounded-md bg-zinc-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            disabled readonly>
                    </div>

                    <!-- Campo nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                        <input type="text" name="name" id="name" minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm  @error('name') border-red-500 @enderror"
                            value="{{ old('name', $user->person->name) }}" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo apellido  -->
                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-white">Apellido</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->person->last_name) }}"
                            minlength="3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm  @error('last_name') border-red-500 @enderror"
                            required>
                        @error('last_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo genero -->
                    <div>
                        <label for="sex" class="block text-sm font-semibold text-white">Género</label>
                        <select name="sex" id="sex"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
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
                        <label for="name" class="block text-sm font-semibold text-white">Nombre de Usuario</label>
                        <input type="text" name="name" value="{{ $user->userName }}"
                            class="mt-1 block w-full rounded-md bg-zinc-900 border border-gray-600 text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            disabled readonly>
                    </div>

                    <!-- Campo email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-white">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm  @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-white">Contraseña</label>
                        <div class="relative">
                            <input id="password" type="password" minlength="8"
                                class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-500 @enderror"
                                name="password" value="{{ old('password') }}" autocomplete="current-password">
                            <!-- Botón para mostrar/ocultar contraseña -->
                            <button type="button" onclick="togglePasswordVisibility('password')"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <i id="eye-icon-password" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo confirmar contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-white">Confirmar
                            Contraseña</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" value="{{ old('password_confirmation') }}"
                                minlength="8"
                                class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                                name="password_confirmation">
                            <!-- Botón para mostrar/ocultar contraseña -->
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <i id="eye-icon-password_confirmation" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-end px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Está seguro de actualizar datos de usuario?')">
                        Actualizar Datos
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
