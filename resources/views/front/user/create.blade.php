@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/user/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Nuevo Usuario</h1>

                {{-- Botón Volver --}}
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-gray-600 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario Persona + Usuario --}}
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Sección Datos Personales --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4">Datos Personales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Campo nombre -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-white">Nombre</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" minlength="3"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campo apellido -->
                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-white">Apellido</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" minlength="3"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('last_name') border-red-500 @enderror"
                                required>
                            @error('last_name')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campo sexo -->
                        <div>
                            <label for="sex" class="block text-sm font-semibold text-white">Género</label>
                            <select name="sex" id="sex"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('sex') border-red-500 @enderror"
                                required>
                                <option value="">Selecciona...</option>
                                <option value="Masculino" {{ old('sex') == 'Masculino' ? 'selected' : '' }}>Masculino
                                </option>
                                <option value="Femenino" {{ old('sex') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sex')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campo ficha -->
                        <div>
                            <label for="license" class="block text-sm font-semibold text-white">Ficha</label>
                            <input type="text" name="license" id="license" value="{{ old('license') }}" minlength="3"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('license') border-red-500 @enderror"
                                required>
                            @error('license')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sección Datos de Usuario --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4">Datos de Usuario</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Campo nombre de Usuario -->
                        <div>
                            <label for="userName" class="block text-sm font-semibold text-white">Nombre de Usuario</label>
                            <input type="text" name="userName" id="userName" value="{{ old('userName') }}" minlength="6" maxlength="12"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('userName') border-red-500 @enderror"
                                required min="6" max="12">
                            @error('userName')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campo correo -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-white">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campo Rol -->
                        <div>
                            <label for="rol" class="block text-sm font-semibold text-white">Rol</label>
                            <select name="rol" id="rol"
                                class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('rol') border-red-500 @enderror"
                                required>
                                <option value="">Selecciona...</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol }}" {{ old('rol') == $rol ? 'selected' : '' }}>
                                        {{ $rol }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rol')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <br>
                    {{-- campos de contraseña --}}
                    <div class="mb-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Campo Contraseña -->
                            <div class="sm:col-span-1">
                                <label for="password" class="block text-sm font-semibold text-white">Contraseña</label>
                                <div class="relative">
                                    <input id="password" type="password" name="password" value="{{ old('password') }}" minlength="8"
                                        class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-500 @enderror"
                                        required>
                                    <!-- Botón para mostrar/ocultar -->
                                    <button type="button" onclick="togglePasswordVisibility('password')"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <i id="eye-icon-password" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Campo Confirmar Contraseña -->
                            <div class="sm:col-span-1">
                                <label for="password_confirmation" class="block text-sm font-semibold text-white">Confirmar
                                    Contraseña</label>
                                <div class="relative">
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                        value="{{ old('password_confrimation') }}" minlength="8"
                                        class="mt-1 block w-full rounded-md bg-gray-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                                        required>
                                    <!-- Botón para mostrar/ocultar -->
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <i id="eye-icon-password_confirmation" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botón Guardar --}}
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Guardar Usuario
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
