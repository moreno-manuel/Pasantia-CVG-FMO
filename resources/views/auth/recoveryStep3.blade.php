@extends('layouts.app')

@section('content')
    <!-- Contenido Principal Centrado -->
    <main class="flex-grow flex items-center justify-center p-4">

        <!-- Contenedor del Formulario -->
        <div class="w-full max-w-sm bg-white rounded-lg shadow p-12 space-y-10 relative">

            <!-- Botón de redirección al Login -->
            <a href="{{ route('login') }}"
                class="absolute top-4 right-4 text-zinc-500 hover:text-red-600 transition-colors duration-300"
                title="Volver al inicio de sesión">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>

            <!-- Título -->
            <h2 class="text-xl font-semibold text-center text-gray-800 border-b pb-2 mt-[-0.5rem]">
                Nueva Contraseña
            </h2>

            <!-- Formulario -->
            <form method="POST" action="{{ route('recovery.storeStep3') }}" class="space-y-7">
                @csrf

                <div class="relative mb-6">
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" type="password"
                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
                            placeholder="Contraseña Nueva" name="password" required>
                    </div>
                    <div class="mt-1 h-5">
                        @error('password')
                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Campo Confirmar Contraseña -->
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input id="password_confirmation" type="password"
                        class="w-full pl-10 px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
                        placeholder="Confirmar Contraseña" name="password_confirmation" required
                        autocomplete="new-password">
                </div>

                <!-- Botón de Acción -->
                <button type="submit"
                    class="w-full bg-zinc-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-key"></i> Actualizar Contraseña
                </button>
            </form>
        </div>
    </main>
@endsection
