@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/nvr/edit.blade.php -->


    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Editar NVR</h1>

            <!-- Botón Volver -->
            <a href="{{ route('nvr.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <!-- Mensaje singular o plural -->
                @if ($errors->count() === 1)
                    <strong class="font-bold">Por favor corrige el siguiente error:</strong>
                @else
                    <strong class="font-bold">Por favor corrige los siguientes errores:</strong>
                @endif

                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('nvr.update', $nvr) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Dirección MAC (No editable) -->
                    <div>
                        <label for="mac" class="block text-sm font-medium text-gray-700">Dirección MAC</label>
                        <input type="text" name="mac" id="mac" value="{{ $nvr->mac }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-500 sm:text-sm"
                            readonly disabled>
                    </div>

                    <!-- Campo Nombre (No editable) -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ $nvr->name }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-500 sm:text-sm"
                            readonly disabled>
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                        <select name="mark" id="mark"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona una marca</option>
                            <option value="Hikvision" {{ $nvr->mark == 'Hikvision' ? 'selected' : '' }}>Hikvision
                            </option>
                            <option value="Dahua" {{ $nvr->mark == 'Dahua' ? 'selected' : '' }}>Dahua</option>
                            <option value="Axis" {{ $nvr->mark == 'Axis' ? 'selected' : '' }}>Axis</option>
                            <option value="Other" {{ $nvr->mark == 'Other' ? 'selected' : '' }}>Otra</option>
                        </select>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $nvr->model) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-medium text-gray-700">Dirección IP</label>
                        <input type="text" name="ip" id="ip" value="{{ old('ip', $nvr->ip) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ejemplo: 192.168.1.20" required>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div>
                        <label for="ports_number" class="block text-sm font-medium text-gray-700">Puertos
                            Disponibles</label>
                        <input type="number" name="ports_number" id="ports_number"
                            value="{{ old('ports_number', $nvr->ports_number) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" required>
                    </div>

                    <!-- Campo Número de Ranuras (No editable) -->
                    <div>
                        <label for="slot_number" class="block text-sm font-medium text-gray-700">Ranuras de Disco
                            Duro</label>
                        <input type="number" name="slot_number" id="slot_number"
                            value="{{ old('slot_number', $nvr->slot_number) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-500 sm:text-sm"
                            min="1" max="4" readonly disabled>
                    </div>

                    <!-- Campo Localidad -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Localidad</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $nvr->location) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Estado del NVR -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado del NVR</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona el estado</option>
                            <option value="Activo" {{ $nvr->status == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ $nvr->status == 'Inactivo' ? 'selected' : '' }}>Inactivo
                            </option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este NVR...">{{ old('description', $nvr->description) }}</textarea>
                    </div>
                </div>

                <!-- Campos dinámicos de Ranuras de Discos -->
                <div id="hdd-form-container" class="mt-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Discos Duros</h3>
                    <p class="text-sm text-gray-500 mb-4">Ingresa la información de los discos duros.</p>
                    <div class="space-y-4" id="hdd-fields">
                        <!-- Generar HDDs estáticos desde PHP -->
                        @php
                            //$slots = $nvr->discos->toArray() ?? [];
                            $slots = $nvr->slotNvr;
                        @endphp

                        @foreach ($slots as $index => $slot)
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200 mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Disco Duro #{{ $index + 1 }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <!-- Serial -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Serial</label>
                                        <input type="text" name="ranura[{{ $index + 1 }}][serial_disco]"
                                            value="{{ old('ranura.' . $loop->iteration . '.serial_disco', $slot['hdd_serial']) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Capacidad actual -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Capacidad (GB)</label>
                                        <input type="number" name="ranura[{{ $index + 1 }}][capacidad_disco]"
                                            value="{{ old('ranura.' . $loop->iteration . '.capacidad_disco', $slot['hdd_capacity']) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            min="1">
                                    </div>

                                    <!-- Capacidad máxima del puerto (No editable) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Capacidad Máxima
                                            (GB)
                                        </label>
                                        <input type="number" name="ranura[{{ $index + 1 }}][capaciad_max_puerto]"
                                            value="{{ old('ranura.' . $loop->iteration . '.capaciad_max_puerto', $slot['capacity_max']) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-500 sm:text-sm"
                                            min="1" readonly disabled>
                                    </div>

                                    <!-- Estado del disco -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                                        <select name="ranura[{{ $index + 1 }}][status]"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">Selecciona...</option>
                                            <option value="Ocupado" {{ $slot['status'] == 'Ocupado' ? 'selected' : '' }}>
                                                Ocupado</option>
                                            <option value="Disponible"
                                                {{ $slot['status'] == 'Disponible' ? 'selected' : '' }}>Disponible
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Actualizar NVR
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
