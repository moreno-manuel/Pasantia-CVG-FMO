@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/report/index.blade.php -->

    <div class="container mx-auto px-4 py-6">
        <!-- Título -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white bg-gray-600 rounded-md px-3 py-1">Reportes</h1>
        </div>

        <!-- Tabla de reportes -->
        <div class="overflow-hidden rounded-lg shadow border border-gray-700 bg-gray-800 w-full max-w-2xl">
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
                                    Ver
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
    </div>
@endsection
