@extends('layouts.app-home')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Título -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-gray-600 rounded-md px-3 py-1">Reportes</h1>
        </div>

        {{-- logo --}}
        <div class="absolute top-4 right-4 z-10 pointer-events-none">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" style="filter: opacity(60%)">
        </div>

        <!-- Contenedor con los dos bloques lado a lado -->
        <div class="flex flex-col md:flex-row gap-6 w-full">
            <!-- Tabla de reportes -->
            <div class="overflow-hidden rounded-lg shadow border border-gray-700 bg-gray-800 flex-1 max-w-2xl">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-white tracking-wider">
                                Tipo de Reporte
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-sm font-medium text-white tracking-wider">
                                Acción
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-900 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $report['name'] ?? 'Reporte sin nombre' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route($report['url']) }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Descargar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-sm text-center text-gray-400">
                                    No hay reportes disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Formulario para descargar logs por rango de fechas-->
            @if (auth()->user()->rol == 'admin')
                <div class="overflow-hidden rounded-lg shadow border border-gray-700 bg-gray-800 flex-1 max-w-xs">
                    <div class="px-6 py-4 bg-gray-700">
                        <h2 class="text-sm font-medium text-white tracking-wider">Archivo Log por Rango de Fechas
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('export.log') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-300">Fecha
                                        Inicio</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-300">Fecha Fin</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Descargar Log
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
