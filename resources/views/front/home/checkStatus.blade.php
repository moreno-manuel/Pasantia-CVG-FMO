@extends('layouts.app-home')

@section('content')

    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1">Monitoreo</h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" style="filter: opacity(60%)">
        </div>

        <br>

        {{-- Tabla de Nvr inactivos  --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-1xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1">Nvr</h1>
        </div>

        <div class="overflow-x-auto rounded-lg shadow border border-zinc-500 bg-zinc-700">
            <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                <thead class="bg-red-900 divide-x divide-white">
                    <tr class="divide-x divide-white">
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">MAC</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Nombre</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-32">Localidad</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">IP</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-white w-24">Status</th>
                    </tr>
                </thead>
                @if ($inactiveNvr->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-white italic">
                            No hay equipos inactivos
                        </td>
                    </tr>
                @else
                    <tbody class="divide-y divide-zinc-200" id="nvr-status-table">
                        @foreach ($inactiveNvr as $nvr)
                            <tr class="hover:bg-zinc-800 transition-colors duration-150">
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
            <h1 class="text-1xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1">Cámaras</h1>
        </div>

        <div class="overflow-x-auto rounded-lg shadow border border-zinc-500 bg-zinc-700">
            <table class="min-w-full shadow-md rounded-lg overflow-hidden divide-gray-700">
                <thead class="bg-red-900 divide-x divide-white">
                    <tr class="divide-x divide-white">
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
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-white italic">
                            No hay equipos inactivos
                        </td>
                    </tr>
                @else
                    <tbody class="divide-y divide-zinc-200" id="camera-status-table">
                        @foreach ($inactiveCameras as $camera)
                            <tr class="hover:bg-zinc-800 transition-colors duration-150">
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
@endsection
