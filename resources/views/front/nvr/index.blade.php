@extends('layouts.app-home')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Nvr</h1>

            <!-- Botón para agregar nueva cámara -->
            <a href="#"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Agregar Nuevo Nvr
            </a>

            <!--tabla -->
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">MAC</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Marca</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Modelo</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">IP</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Capacidad Puertos</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Puertos Usados</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Puertos Disponibles</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Descripción</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Ejemplo de datos estáticos (reemplaza con datos reales desde el controlador) -->
                    @php
                        $dispositivos = [
                            [
                                'mac' => '00:1A:2B:3C:4D:5E',
                                'mark' => 'Hikvision',
                                'model' => 'DS-2CD2042WD-I',
                                'name' => 'Cámara 1',
                                'ip' => '192.168.1.10',
                                'volumen_1' => '5',
                                'volumen_2' => '5',
                                'number_ports' => '10',
                                'use_ports' => '5',
                                'dis_ports' => '5',
                                'description' => 'Activo',
                            ],
                            [
                                'mac' => '00:1A:2B:3C:4D:5E',
                                'mark' => 'Hikvision',
                                'model' => 'DS-2CD2042WD-I',
                                'name' => 'Cámara 1',
                                'ip' => '192.168.1.10',
                                'volumen_1' => '5',
                                'volumen_2' => '5',
                                'number_ports' => '10',
                                'use_ports' => '5',
                                'dis_ports' => '5',
                                'description' => 'inactio',
                            ],
                            [
                                'mac' => '00:1A:2B:3C:4D:5E',
                                'mark' => 'Hikvision',
                                'model' => 'DS-2CD2042WD-I',
                                'name' => 'Cámara 1',
                                'ip' => '192.168.1.10',
                                'volumen_1' => '5',
                                'volumen_2' => '5',
                                'number_ports' => '10',
                                'use_ports' => '5',
                                'dis_ports' => '5',
                                'description' => 'inactivo',
                            ],
                        ];
                    @endphp

                    @foreach ($dispositivos as $dispositivo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['mac'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['mark'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['model'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['ip'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['number_ports'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['use_ports'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['dis_ports'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dispositivo['description'] }}</td>

                            <td class="px-6 py-4 text-sm space-x-2">
                                <!-- Botón Ver -->
                                <a href="#"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Ver
                                </a>
                                <!-- Botón Eliminar -->
                                <form action="#" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
