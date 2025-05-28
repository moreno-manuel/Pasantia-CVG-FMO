@extends('layouts.app-home')
@section('content')
    <!-- resources/views/switch/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Nuevo Switch</h1>

            <!-- Botón Volver -->
            <a href="{{ route('switch.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        {{-- formulario crear switch --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('switch.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Serial -->
                    <div>
                        <label for="serial" class="block text-sm font-medium text-gray-700">Serial</label>
                        <input type="text" name="serial" value = '{{ old('serial') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ejemplo: SN-JK890123" required>
                        @error('serial')
                            <br>
                            <span class="bg-red-600 text-white py-2 px-4 rounded font-bold"
                                style="font-size: 12px">{{ $message }}</span>
                            </br>
                        @enderror
                    </div>

                    <!-- Campo modelo -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <select name="model" value = '{{ old('model') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona una marca</option>
                            <option value="Hikvision">Hikvision</option>
                            <option value="Dahua">Dahua</option>
                            <option value="Axis">Axis</option>
                            <option value="Other">Otra</option>
                        </select>
                    </div>

                    <!-- Campo Número de Puertos -->
                    <div>
                        <label for="num_puertos" class="block text-sm font-medium text-gray-700">Número de
                            Puertos</label>
                        <input type="number" name="number_ports" value = '{{ old('number_ports') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" required>
                    </div>

                    <!-- Campo Persona Usuario -->
                    <div>
                        <label for="persona_usuario" class="block text-sm font-medium text-gray-700">Persona
                            Usuario</label>
                        <input type="text" name="user_person" value = '{{ old('user_person') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Nombre del responsable" required>
                    </div>

                    <!-- Campo Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="status" value = '{{ old('status') }}'
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona el estado</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Mantenimiento">En Mantenimiento</option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" value = '{{ old('description') }}' rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este switch..."></textarea>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150"
                        onclick="return confirm('¿Estás seguro de guardar este Switch?')">
                        Guardar Switch
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
