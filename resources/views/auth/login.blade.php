@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 text-white p-4">
            <h1 class="text-2xl font-bold text-center">KrhonosVision</h1>
        </header>

        <!-- Mostrar error general -->
        @if (session('status'))
            <div class="mb-4 text-red-500 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Contenido Principal Centrado -->
        <main class="flex-grow flex items-center justify-center p-4">
            <!-- Contenedor del Login -->
            <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 space-y-6">
                <h2 class="text-2xl font-bold text-center text-gray-800">Iniciar Sesión</h2>

                <br>

                <!-- Formulario -->
                <form method="POST" action="/login"" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-gray-950 mb-2">Usuario</label>
                        <input id="name" type="text"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="username" name="userName" required autocomplete="username" autofocus>

                        @error('userName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-gray-950 mb-2">Contraseña</label>
                        <input id="password" type="password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="••••••••" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-800 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        Ingresar
                    </button>

                    <br>
                    <br>


                    <div>
                        <a class="block text-gray-700 mb-2 text-center">¿Olvidó su password?<a>

                    </div>

                </form>

            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white p-4 mt-auto">
            <p class="text-center text-sm">© 2025. Ferrominera. Seguridad Tecnologica </p>
        </footer>
    </div>
@endsection
