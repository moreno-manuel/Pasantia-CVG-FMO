@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/perfil/editQuestion.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="bg-zinc-600 shadow overflow-hidden sm:rounded-lg p-6 border border-gray-700">

            {{-- Título y botón volver --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Editar Preguntas de Seguridad</h1>

                <!-- Botón Volver -->
                <a href="{{ route('perfil.edit', ['user' => auth()->user()->userName]) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-semibold rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-yellow-700 hover:shadow-md hover:-translate-y-px text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('security.update', ['user' => auth()->user()->userName]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo pregunta 1 -->
                    <div>
                        <label for="question_1" class="block text-sm font-semibold text-white">Pregunta 1</label>
                        <select name="question_1" id="question_1"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($questions as $question)
                                <option value="{{ $question }}"
                                    {{ old('question_1', isset($user->questionsRecovery) ? $user->questionsRecovery->question_1 : '') == $question ? 'selected' : '' }}>
                                    {{ $question }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo respuesta 1 -->
                    <div>
                        <label for="answer_1" class="block text-sm font-semibold text-white">Respuesta 1</label>
                        <input type="text" name="answer_1"
                            value="{{ old('answer_1', $user->questionsRecovery->answer_1) }}"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('answer_1') border-red-500 @enderror"
                            required>
                        @error('answer_1')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo pregunta 2 -->
                    <div>
                        <label for="question_2" class="block text-sm font-semibold text-white">Pregunta 2</label>
                        <select name="question_2" id="question_2"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($questions as $question)
                                <option value="{{ $question }}"
                                    {{ old('question_2', isset($user->questionsRecovery) ? $user->questionsRecovery->question_2 : '') == $question ? 'selected' : '' }}>
                                    {{ $question }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo respuesta 2 -->
                    <div>
                        <label for="answer_2" class="block text-sm font-semibold text-white">Respuest 2</label>
                        <input type="text" name="answer_2"
                            value="{{ old('answer_2', $user->questionsRecovery->answer_2) }}"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('answer_2') border-red-500 @enderror"
                            required>
                        @error('answer_2')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo pregunta 3 -->
                    <div>
                        <label for="question_3" class="block text-sm font-semibold text-white">Pregunta 3</label>
                        <select name="question_3" id="question_3"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Seleccione..</option>
                            @foreach ($questions as $question)
                                <option value="{{ $question }}"
                                    {{ old('question_3', isset($user->questionsRecovery) ? $user->questionsRecovery->question_3 : '') == $question ? 'selected' : '' }}>
                                    {{ $question }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo respuesta 3 -->
                    <div>
                        <label for="answer_3" class="block text-sm font-semibold text-white">Respuesta 3</label>
                        <input type="text" name="answer_3"
                            value="{{ old('answer_3', $user->questionsRecovery->answer_3) }}"
                            class="mt-1 block w-full rounded-md bg-zinc-700 border border-gray-600 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('answer_3') border-red-500 @enderror"
                            required>
                        @error('answer_3')
                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-green-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        onclick="return confirm('¿Estás seguro de guardar las preguntas de seguridad?')">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
