@extends('layouts.app')

@section('content')
    <!-- Contenido Principal Centrado -->
    <main class="flex-grow flex items-center justify-center p-4">

        <!-- Contenedor del Formulario -->
        <div class="w-full max-w-md bg-white rounded-lg shadow p-12 space-y-10 relative">

            <!-- Botón de redirección al Login -->
            <a href="{{ route('login') }}"
                class="absolute top-4 right-4 text-zinc-500 hover:text-red-600 transition-colors duration-300"
                title="Volver al inicio de sesión">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>

            <!-- Título -->
            <h2 class="text-xl font-semibold text-center text-gray-800 border-b pb-2 mt-[-0.5rem]">
                Preguntas de Seguridad
            </h2>

            <!-- Formulario -->
            @if ($user->questionsRecovery != '')
                <form method="POST" action="{{ route('recovery.storeStep2') }}" class="space-y-7">
                    @csrf

                    <!-- Preguntas de seguridad -->

                    <div class="relative">
                        <label class="block text-black mb-2 text-sm font-medium">
                            {{ $user->questionsRecovery->question_1 }}
                        </label>
                        <input type="text" name="answer_1" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 @error('answer_1') border-red-500 @enderror">
                        @error('answer_1')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative">
                        <label class="block text-black mb-2 text-sm font-medium">
                            {{ $user->questionsRecovery->question_2 }}
                        </label>
                        <input type="text" name="answer_2" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 @error('answer_2') border-red-500 @enderror">
                        @error('answer_2')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative">
                        <label class="block text-black mb-2 text-sm font-medium">
                            {{ $user->questionsRecovery->question_3 }}
                        </label>
                        <input type="text" name="answer_3" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 @error('answer_3') border-red-500 @enderror">
                        @error('answer_3')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botón de acción -->
                    <button type="submit"
                        class="w-full bg-zinc-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i> Verificar
                    </button>

                </form>
            @else
                <div class="text-center text-gray-600 text-sm">
                    No hay preguntas de seguridad configuradas para este Usuario.
                </div>
            @endif
        </div>
    </main>
@endsection
