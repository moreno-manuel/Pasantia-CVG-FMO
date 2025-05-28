@extends('layouts.app-home') <!-- Asegúrate de tener layouts/app.blade.php -->

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- encabezado y boton agregar --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Cámaras</h1>
            <a href="{{ route('camara.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Agregar Nueva Cámara
            </a>

            <!--tabla -->
        </div>

        <!-- Filtros -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NVR</label>
                    <input type="text" name="nvr"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <select name="marca"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Todas</option>
                        <option value="Hikvision">Hikvision</option>
                        <option value="Dahua">Dahua</option>
                        <option value="Axis">Axis</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                    <input type="text" name="modelo"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Todos</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        {{-- tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
<<<<<<< HEAD
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">MAC</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-24">NVR</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-24">Marca</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-24">Modelo</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Nombre</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Ubicación</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-24">IP</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-24">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 w-32">Acciones</th>
=======
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">MAC</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Marca</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Modelo</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">IP</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ubicación</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">NVR Conexión</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
>>>>>>> def195e204544cf70835e80cb5b845ad104f78ee
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $dispositivos = [
                            [
                                'mac' => '00:1A:2B:3C:4D:5E',
                                'nvr_id' => 'NVR001',
                                'marca' => 'Hikvision',
                                'modelo' => 'DS-2CD2042WD-I',
                                'nombre' => 'Cámara 1',
                                'ubicacion' => 'Entrada Principal',
                                'ip' => '192.168.1.10',
                                'status' => 'Activo',
                                'descripcion' => 'Cámara de alta resolución para entrada principal',
                            ],
                            [
                                'mac' => '00:1B:44:11:3A:B7',
                                'nvr_id' => 'NVR002',
                                'marca' => 'Dahua',
                                'modelo' => 'IPC-HFW1435M-LED',
                                'nombre' => 'Cámara 2',
                                'ubicacion' => 'Estacionamiento',
                                'ip' => '192.168.1.11',
                                'status' => 'Inactivo',
                                'descripcion' => 'Cámara para control de estacionamiento',
                            ],
                            [
                                'mac' => '00:1C:B3:01:02:03',
                                'nvr_id' => 'NVR003',
                                'marca' => 'Axis',
                                'modelo' => 'M1124',
                                'nombre' => 'Cámara 3',
                                'ubicacion' => 'Sala de Servidores',
                                'ip' => '192.168.1.12',
                                'status' => 'Activo',
                                'descripcion' => 'Cámara de seguridad para sala de servidores',
                            ],
                        ];
                    @endphp

                    @foreach ($dispositivos as $dispositivo)
                        <tr class="hover:bg-gray-50">
<<<<<<< HEAD
                            <td class="px-6 py-4 text-sm text-gray-900 truncate">{{ $dispositivo['mac'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['nvr_id'] }}</td>
=======
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['mac'] }}</td>
>>>>>>> def195e204544cf70835e80cb5b845ad104f78ee
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['marca'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['modelo'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['nombre'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['ip'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['ubicacion'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['nvr_id'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $dispositivo['status'] === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $dispositivo['status'] }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-2">
                                    <!-- Botón Ver -->
                                    <a href="#"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Ver
                                    </a>
                                    <!-- Botón Editar -->
                                    <a href="#"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        Editar
                                    </a>
                                    <!-- Botón Eliminar -->
                                    <form action="#" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        {{-- <div class="mt-6">
            {{ $dispositivos->links() }}
        </div> --}}
    </div>
@endsection
