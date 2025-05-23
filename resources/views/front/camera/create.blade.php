@extends('layouts.app-home')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ $camera ? 'Editar Cámara' : 'Agregar Nueva Cámara' }}</h1>

        <form action="{{ $camera ? route('cameras.update', $camera->id) : route('cameras.store') }}" method="POST"
            class="space-y-6 bg-white p-6 rounded-lg shadow-md">
            @csrf
            @if ($camera)
                @method('PUT')
            @endif

            <!-- Campo MAC -->
            <div>
                <label for="mac" class="block text-sm font-medium text-gray-700">MAC</label>
                <input type="text" name="mac" id="mac" value="{{ old('mac', $camera->mac ?? '') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('mac') @enderror" required>
                @error('mac')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo NVR (Select) -->
            <div>
                <label for="nvr_id" class="block text-sm font-medium text-gray-700">NVR</label>
                <select name="nvr_id" id="nvr_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('nvr_id') @enderror">
                    <option value="">Selecciona un NVR</option>
                    <!-- Opciones dinámicas (ejemplo) -->
                    @foreach ($nvrOptions as $nvr)
                        <option value="{{ $nvr }}"
                            {{ old('nvr_id', $camera->nvr_id ?? '') == $nvr ? 'selected' : '' }}>
                            {{ $nvr }}
                        </option>
                    @endforeach
                </select>
                @error('nvr_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Marca -->
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" name="marca" id="marca" value="{{ old('marca', $camera->marca ?? '') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('marca') @enderror">
                @error('marca')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Modelo -->
            <div>
                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $camera->modelo ?? '') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('modelo') @enderror">
                @error('modelo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $camera->nombre ?? '') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('nombre') @enderror" required>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Ubicación -->
            <div>
                <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="ubicacion" id="ubicacion"
                    value="{{ old('ubicacion', $camera->ubicacion ?? '') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('ubicacion') @enderror">
                @error('ubicacion')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo IP -->
            <div>
                <label for="ip" class="block text-sm font-medium text-gray-700">IP</label>
                <input type="text" name="ip" id="ip" value="{{ old('ip', $camera->ip ?? '') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('ip') @enderror" required>
                @error('ip')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Status (Select) -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('status') @enderror" required>
                    <option value="">Selecciona un estado</option>
                    <option value="Activo" {{ old('status', $camera->status ?? '') == 'Activo' ? 'selected' : '' }}>Activo
                    </option>
                    <option value="Inactivo" {{ old('status', $camera->status ?? '') == 'Inactivo' ? 'selected' : '' }}>
                        Inactivo</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ $camera ? 'Actualizar' : 'Guardar' }}
                </button>
                <a href="{{ route('cameras.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
