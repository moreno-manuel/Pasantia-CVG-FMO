@extends('layouts.app')

@section('content')
    <!-- Contenido Principal-->
    <main class="flex-grow flex items-center justify-center p-4">

        <!-- Contenedor del Formulario -->
        <div class="w-full max-w-sm bg-white rounded-lg shadow p-12 space-y-10 relative">

            <!-- Botón de redirección al Login -->
            <a href="{{ route('login') }}"
                class="absolute top-4 right-4 text-gray-600 hover:text-blue-600 transition-colors duration-300"
                title="Volver al inicio de sesión">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>

            <!-- Título -->
            <h2 class="text-xl font-semibold text-center text-gray-800 border-b pb-2 mt-[-0.5rem]">
                Recuperar Cuenta
            </h2>

            <!-- Formulario -->
            <form method="POST" action="{{ route('recovery.storeStep1') }}" class="space-y-7">
                @csrf

                <!-- Campo Usuario -->
                <div class="relative mb-6">
                    <!-- Contenedor para ícono e input -->
                    <div class="relative flex items-center">
                        <!-- Icono -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>

                        <!-- Campo de entrada  -->
                        <input id="userName" type="text"
                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('userName') border-red-500 @enderror"
                            placeholder="Usuario" name="userName" required>
                    </div>

                    <!-- Contenedor para mensaje de error -->
                    <div class="mt-1 h-5">
                        @error('userName')
                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Campo Ficha -->
                <div class="relative mb-6">
                    <!-- Contenedor para ícono e input -->
                    <div class="relative flex items-center">
                        <!-- Icono -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-id-badge text-gray-400"></i>
                        </div>

                        <!-- Campo de entrada-->
                        <input id="license" type="text"
                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('license') border-red-500 @enderror"
                            placeholder="N° de Ficha" name="license" required>
                    </div>

                    <!-- Contenedor fijo para mensaje de error -->
                    <div class="mt-1 h-5">
                        @error('license')
                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Botón de Acción  -->
                <button type="submit"
                    class="w-full bg-gray-800 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded 
                    transition-colors duration-300 ease-in-out flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-right"></i> Continuar
                </button>
            </form>
        </div>
    </main>
@endsection
