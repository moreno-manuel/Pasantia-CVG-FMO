@extends('layouts.app-home')
@section('content')
    <!-- resources/views/nvr/create.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Nuevo NVR</h1>

            <!-- Botón Volver -->
            <a href="{{ route('nvr.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                Volver
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('nvr.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo Dirección MAC -->
                    <div>
                        <label for="mac" class="block text-sm font-medium text-gray-700">Dirección MAC</label>
                        <input type="text" name="mac" id="mac"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ejemplo: 00:1A:2B:3C:4D:5E" required>
                    </div>

                    <!-- Campo Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                        <select name="marca" id="marca"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona una marca</option>
                            <option value="Hikvision">Hikvision</option>
                            <option value="Dahua">Dahua</option>
                            <option value="Axis">Axis</option>
                            <option value="Other">Otra</option>
                        </select>
                    </div>

                    <!-- Campo Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="modelo" id="modelo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Campo IP -->
                    <div>
                        <label for="ip" class="block text-sm font-medium text-gray-700">Dirección IP</label>
                        <input type="text" name="ip" id="ip"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ejemplo: 192.168.1.20" required>
                    </div>

                    <!-- Campo Puertos Disponibles -->
                    <div>
                        <label for="puertos_disponibles" class="block text-sm font-medium text-gray-700">Puertos
                            Disponibles</label>
                        <input type="number" name="puertos_disponibles" id="puertos_disponibles"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" required>
                    </div>

                    <!-- Campo Número de Discos Duros -->
                    <div>
                        <label for="num_discos_duros" class="block text-sm font-medium text-gray-700">Número de Discos
                            Duros</label>
                        <input type="number" name="num_discos_duros" id="num_discos_duros"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="0" oninput="generateHDDForm(this.value)">
                    </div>

                    <!-- Campo Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado del NVR</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Selecciona el estado</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Describe brevemente este NVR..."></textarea>
                    </div>
                </div>

                <!-- Campos dinámicos de HDDs -->
                <div id="hdd-form-container" class="mt-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Discos Duros</h3>
                    <p class="text-sm text-gray-500 mb-4">Ingresa la información de los discos duros.</p>
                    <div class="space-y-4" id="hdd-fields">
                        <!-- Aquí se insertan los campos dinámicos -->

                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Guardar NVR
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            console.log("Valor recibido:", count);

            function generateHDDForm(count) {
                const container = document.getElementById('hdd-fields');
                if (!container) {
                    console.error("No se encontró el contenedor #hdd-fields");
                    return;
                }

                container.innerHTML = ''; // Limpiar contenido anterior
                count = parseInt(count);

                if (isNaN(count) || count <= 0) {
                    console.log("Número inválido de discos:", count);
                    return;
                }

                for (let i = 1; i <= count; i++) {
                    const html = `
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200 mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Disco Duro #${i}</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Serial -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Serial</label>
                        <input type="text" name="discos[${i}][serial]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Capacidad actual -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacidad (GB)</label>
                        <input type="number" name="discos[${i}][capacidad]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1">
                    </div>

                    <!-- Capacidad máxima del puerto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacidad Máxima (GB)</label>
                        <input type="number" name="discos[${i}][capacidad_max]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1">
                    </div>

                    <!-- Estado del disco -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="discos[${i}][status]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Selecciona...</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Fuera de servicio">Fuera de servicio</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
                    container.insertAdjacentHTML('beforeend', html);
                }
            }
        </script>
    @endpush
@endsection
