<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'App')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

@livewireStyles

<body class="bg-gray-100 min-h-screen flex flex-col">
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg fixed h-full top-0 left-0 z-20 overflow-y-auto">
            @include('partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-6 overflow-y-auto">

            <!-- Contenedor para posicionamiento -->
            <div class="relative">
                <!-- Mensaje de éxito -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                        x-transition:enter="transition-[transform,opacity] duration-[700ms] ease-out"
                        x-transition:enter-start="translate-y-[120%] opacity-0 scale-95"
                        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                        x-transition:leave="transition-[transform,opacity] duration-[300ms] ease-in"
                        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                        x-transition:leave-end="translate-y-[120%] opacity-0 scale-95"
                        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-96 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md"
                        role="alert">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button @click="show = false"
                                class="text-green-700 hover:text-green-900 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Mensaje de advertencia -->
                @if (session('warning'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                        x-transition:enter="transition-[transform,opacity] duration-[700ms] ease-out"
                        x-transition:enter-start="translate-y-[120%] opacity-0 scale-95"
                        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                        x-transition:leave="transition-[transform,opacity] duration-[300ms] ease-in"
                        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                        x-transition:leave-end="translate-y-[120%] opacity-0 scale-95"
                        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-96 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg shadow-md"
                        role="alert">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span>{{ session('warning') }}</span>
                            </div>
                            <button @click="show = false"
                                class="text-yellow-700 hover:text-yellow-900 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Contenido dinámico -->
            @yield('content')
        </main>
    </div>

    <!-- Footer estático al final -->
    <footer class="text-black p-4 z-10 mt-auto shadow-md">
        <div class="text-center">
            <p class="text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnológica</p>
        </div>
    </footer>

    @stack('scripts')
    @livewireScripts
</body>

</html>
