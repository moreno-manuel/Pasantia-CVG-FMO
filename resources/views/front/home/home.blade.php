@extends('layouts.app-home')
@section('content')
    <div class="flex items-center justify-between mb-6">

        <div class="w-14"></div>

        <!-- Título -->
        <h1 class="text-2xl font-bold text-white bg-zinc-500 rounded-md px-3 py-1 text-center">
            Resumen de Estados de NVR y Cámaras
        </h1>

        <!-- Logo -->
        <div>
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" class="h-14 opacity-60">
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>

    <!-- Cuadros de NVR y Cámaras -->
    <div class="flex flex-col md:flex-row gap-8 mb-8">

        <!-- Cuadro NVR -->
        <div class="bg-zinc-700 rounded-lg shadow-lg p-6 border border-red-900 flex-1">
            <h2 class="text-xl font-bold text-white mb-4 text-center">NVR</h2>
            <div class="flex justify-between gap-3">
                <div
                    class="bg-zinc-600 text-white p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20">
                    <p class="text-sm font-medium">Total</p>
                    <p class="text-2xl font-bold">{{ $nvrAll }}</p>
                </div>
                <div
                    class="bg-zinc-600 text-green-500 p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20 border border-green-500">
                    <p class="text-sm font-medium">Online</p>
                    <p class="text-2xl font-bold">{{ $nvrOnline }}</p>
                </div>
                <div
                    class="bg-zinc-600 text-red-500 p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20 border border-red-500">
                    <p class="text-sm font-medium">Offline</p>
                    <p class="text-2xl font-bold">{{ $nvrOffline }}</p>
                </div>
                <div
                    class="bg-zinc-600 text-yellow-500 p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20 border border-yellow-500">
                    <p class="text-sm font-medium">Connecting</p>
                    <p class="text-2xl font-bold">{{ $nvrConecting }}</p>
                </div>
            </div>
        </div>

        <!-- Cuadro Cámaras -->
        <div class="bg-zinc-700 rounded-lg shadow-lg p-6 border border-red-900 flex-1">
            <h2 class="text-xl font-bold text-white mb-4 text-center">Cámaras</h2>
            <div class="flex justify-between gap-3">
                <div
                    class="bg-zinc-600 text-white p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20">
                    <p class="text-sm font-medium">Total</p>
                    <p class="text-2xl font-bold">{{ $cameraAll }}</p>
                </div>
                <div
                    class="bg-zinc-600 text-green-500 p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20 border border-green-500">
                    <p class="text-sm font-medium">Online</p>
                    <p class="text-2xl font-bold">{{ $cameraOnline }}</p>
                </div>
                <div
                    class="bg-zinc-600 text-red-500 p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20 border border-red-500">
                    <p class="text-sm font-medium">Offline</p>
                    <p class="text-2xl font-bold">{{ $cameraOffline }}</p>
                </div>
                <div
                    class="bg-zinc-600 text-yellow-500 p-4 rounded-lg shadow flex-1 flex flex-col items-center justify-center aspect-square min-h-20 border border-yellow-500">
                    <p class="text-sm font-medium">Connecting</p>
                    <p class="text-2xl font-bold">{{ $cameraConecting }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
