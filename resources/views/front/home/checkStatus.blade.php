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
                            No hay cámaras inactivas
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

        <br>
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
                            No hay Nvr Inactivo
                        </td>
                    </tr>
                @else
                    <tbody class="divide-y divide-gray-200" id="camera-status-table">
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
    </div>

    {{-- script para actualizacion sin recagar pagina --}}
    @push('scripts')
        <script>
            function updateCameraStatus() {
                fetch("{{ route('test.check') }}")
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('camera-status-table');
                        tableBody.innerHTML = ''; // Limpiar tabla

                        // Filtrar solo cámaras inactivas
                        const inactiveCameras = data.filter(camera => camera.status === 'offline' || camera.status ===
                            'conecting...');

                        if (inactiveCameras.length === 0) {
                            tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                                No hay cámaras inactivas
                            </td>
                        </tr>
                    `;
                            return;
                        }

                        // Agregar cámaras inactivas a la tabla
                        inactiveCameras.forEach(camera => {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-gray-900 transition-colors duration-150';

                            row.innerHTML = `
                        <td class="px-6 py-4 text-center text-sm text-white">${camera.mac}</td>
                        <td class="px-6 py-4 text-center text-sm text-white">${camera.nvr}</td>
                        <td class="px-6 py-4 text-center text-sm text-white">${camera.name}</td>
                        <td class="px-6 py-4 text-center text-sm text-white">${camera.location}</td>
                        <td class="px-6 py-4 text-center text-sm text-white">${camera.ip}</td>
                        <td class="px-6 py-4 text-center text-sm">
                            <span id="status-${camera.mac}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-600 text-red-100">
                                ${camera.status}
                            </span>
                        </td>
                    `;

                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error al actualizar estados:', error));
            }

            setInterval(updateCameraStatus, 15000); // Actualiza cada 10 segundos
        </script>
    @endpush
@endsection
