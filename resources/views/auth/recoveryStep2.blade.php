@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-bold mb-6 text-center">Preguntas de Seguridad</h2>


            @if ($user->questionsRecovery != '')
                <form method="POST" action="{{ route('recovery.storeStep2') }}">
                    @csrf

                    @foreach ($user->questionsRecovery as $question)
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">{{ $question->question }}</label>
                            <input type="text" name="answer_{{ $loop }}" required
                                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    @endforeach

                    @if (session('error'))
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                        Verificar
                    </button>
                </form>
            @else
                <h2 class="text-2xl font-bold mb-6 text-center">Pruebas</h2>
            @endif
        </div>
    </div>
@endsection
