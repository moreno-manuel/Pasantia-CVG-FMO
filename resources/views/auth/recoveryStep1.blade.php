@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-bold mb-6 text-center">Recuperar Cuenta</h2>

            <form method="POST" action="{{ route('recovery.storeStep1') }}">
                @csrf
                <div class="mb-6">
                    <label for="userName" class="block text-gray-700 mb-2">Nombre de Usuario</label>
                    <input type="text" name="userName" id="userName" required
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('userName') border-red-500 @enderror">
                    @error('userName')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="license" class="block text-gray-700 mb-2">Ficha</label>
                    <input type="text" name="license" id="license" required
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('license') border-red-500 @enderror"
                        placeholder="Ingrese su N° de Ficha">
                    @error('license')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                    Continuar
                </button>
            </form>
        </div>
    </div>
@endsection
