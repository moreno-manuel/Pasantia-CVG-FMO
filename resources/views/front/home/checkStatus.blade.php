@extends('layouts.app-home')

@section('content')

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-gray-600 rounded-md px-3 py-1">Monitoreo</h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" style="filter: opacity(60%)">
        </div>

        <br>

        {{-- Tabla de Nvr inactivos  --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-1xl font-bold text-white bg-gray-600 rounded-md px-3 py-1">Nvr</h1>
        </div>

        <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-gray-800">
            <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                <thead class="bg-gray-700 divide-x divide-blue-400">
                    <tr class="divide-x divide-blue-400">
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">MAC</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Nombre</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Localidad</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">IP</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Status</th>
                    </tr>
                </thead>
                @if ($inactiveNvr->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                            No hay equipos inactivos
                        </td>
                    </tr>
                @else
                    <tbody class="divide-y divide-gray-200" id="nvr-status-table">
                        @foreach ($inactiveNvr as $nvr)
                            <tr class="hover:bg-gray-900 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $nvr['mac'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $nvr['name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $nvr['location'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $nvr->ip }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if ($nvr->status == 'offline') bg-red-600 text-red-100
                                            @else bg-yellow-600 text-yellow-100 @endif">
                                        {{ $nvr->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>

        <br>
        <br>

        {{-- Tabla de cámaras inactivas --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-1xl font-bold text-white bg-gray-600 rounded-md px-3 py-1">Cámaras</h1>
        </div>

        <div class="overflow-x-auto rounded-lg shadow border border-gray-700 bg-gray-800">
            <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                <thead class="bg-gray-700 divide-x divide-blue-400">
                    <tr class="divide-x divide-blue-400">
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">MAC</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">NVR/Conexión</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Nombre</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Localidad</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">IP</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Status</th>
                    </tr>
                </thead>
                @if ($inactiveCameras->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                            No hay equipos inactivos
                        </td>
                    </tr>
                @else
                    <tbody class="divide-y divide-gray-200" id="camera-status-table">
                        @foreach ($inactiveCameras as $camera)
                            <tr class="hover:bg-gray-900 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $camera['mac'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $camera->nvr->name }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $camera['name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $camera['location'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-white">{{ $camera->ip }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <span id="status-{{ $camera->mac }}"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if ($camera->status == 'offline') bg-red-600 text-red-100
                                            @else bg-yellow-600 text-yellow-100 @endif">
                                        {{ $camera->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>

    </div>

    {{-- para enviar los datos actualizados  --}}
    @push('scripts')
        <script>
            function updateDeviceStatus() {
                fetch("{{ route('test.check') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Actualiza cámaras
                        updateTable('camera-status-table', data.cameras, 'camera');

                        // Actualiza NVRs
                        updateTable('nvr-status-table', data.nvrs, 'nvr');
                    })
                    .catch(error => {
                        console.error('Error al actualizar estados:', error);
                        showError('camera-status-table', 'Error en cámaras');
                        showError('nvr-status-table', 'Error en Nvr');
                    });
            }

            function updateTable(tableId, devices, type) {
                const tableBody = document.getElementById(tableId);
                tableBody.innerHTML = '';

                if (devices.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                    No hay equipos inactivos
                </td>
            `;
                    tableBody.appendChild(row);
                    return;
                }

                devices.forEach(device => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-900 transition-colors duration-150';

                    if (type === 'camera') {
                        row.innerHTML = `
                    <td class="px-6 py-4 text-center text-sm text-white">${device.mac}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.nvr || '-'}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.name}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.location}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.ip}</td>
                    <td class="px-6 py-4 text-center text-sm">
                        <span id="status-${device.mac}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${device.status === 'offline' ? 'bg-red-600 text-red-100' : 'bg-yellow-600 text-yellow-100'}">
                            ${device.status}
                        </span>
                    </td>
                `;
                    } else {
                        row.innerHTML = `
                    <td class="px-6 py-4 text-center text-sm text-white">${device.mac}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.name}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.location}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.ip}</td>
                    <td class="px-6 py-4 text-center text-sm">
                        <span id="nvr-status-${device.mac}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${device.status === 'offline' ? 'bg-red-600 text-red-100' : 'bg-yellow-600 text-yellow-100'}">
                            ${device.status}
                        </span>
                    </td>
                `;
                    }

                    tableBody.appendChild(row);
                });
            }

            function showError(tableId, message) {
                const tableBody = document.getElementById(tableId);
                tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">
                    ${message}
                </td>
            </tr>
        `;
            }

            // Inicia la actualización
            updateDeviceStatus();
            setInterval(updateDeviceStatus, 15000); // Cada 15 segundos
        </script>
    @endpush
@endsection
