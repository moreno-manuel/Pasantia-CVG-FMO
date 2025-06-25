@extends('layouts.app')

@section('content')
    <!-- Contenido Principal Centrado -->
    <main class="flex-grow flex items-center justify-center p-4">
        <!-- Contenedor del Login -->
        <div class="w-full max-w-md bg-white rounded-lg shadow p-6 space-y-4">

            <!-- Título estilizado -->
            <h2 class="text-xl font-semibold text-center text-gray-800 border-b pb-2">Iniciar Sesión</h2>

            <!-- Formulario -->
            <form method="POST" action="/login" class="space-y-4">
                @csrf

                <!-- Campo Usuario -->
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input id="name" type="text"
                        class="w-full pl-10 px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('userName') border-red-500 @enderror"
                        placeholder="Usuario" name="userName" required autocomplete="username" autofocus>
                    @error('userName')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input id="password" type="password"
                        class="w-full pl-10 px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror"
                        placeholder="Contraseña" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón de Ingreso -->
                <button type="submit"
                    class="w-full bg-gray-800 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>

                <!-- Enlace de recuperación -->
                <div class="text-center mt-2">
                    <a href="{{ route('recovery.showStep1') }}" class="text-xs text-gray-600 hover:text-blue-600">
                        ¿Olvidó su contraseña?
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
