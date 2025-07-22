@extends('layouts.app')

@section('content')
    <!-- Contenido Principal Centrado -->
    <main class="flex-grow flex items-center justify-center p-4">

        <!-- Contenedor del Formulario -->
        <div class="w-full max-w-sm bg-white rounded-lg shadow p-12 space-y-10">

            <!-- Título -->
            <h2 class="text-xl font-semibold text-center text-gray-800 border-b pb-2 mt-[-0.5rem]">
                Iniciar Sesión
            </h2>

            <!-- Formulario -->
            <form method="POST" action="/login" class="space-y-7">
                @csrf

                <!-- Campo Usuario -->
                <div class="relative mb-6">
                    <!-- Contenedor para ícono e input -->
                    <div class="relative flex items-center">
                        <!-- Icono fijo -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>

                        <!-- Campo de entrada -->
                        <input id="name" type="text"
                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 @error('userName') border-red-500 @enderror"
                            placeholder="Usuario" name="userName" required autocomplete="username" autofocus>
                    </div>
                    <!-- Contenedor fijo para mensaje de error -->
                    <div class="mt-1 h-5">
                        @error('userName')
                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Campo Contraseña -->
                <div class="relative mb-6">
                    <!-- Contenedor para ícono e input -->
                    <div class="relative flex items-center">
                        <!-- Icono fijo -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>

                        <!-- Campo de entrada -->
                        <input id="password" type="password"
                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 @error('password') border-red-500 @enderror"
                            placeholder="Contraseña" name="password" required autocomplete="current-password">
                    </div>

                    <!-- Contenedor fijo para mensaje de error -->
                    <div class="mt-1 h-5">
                        @error('password')
                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-zinc-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded transition-all duration-300 ease-in-out flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>

                <!-- Enlace de recuperación -->
                <div class="text-center mt-6">
                    <a href="{{ route('recovery.showStep1') }}" class="text-xs text-zinc-500 hover:text-red-600">
                        ¿Olvidó su contraseña?
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
