<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'App')</title>
    <script src="https://cdn.tailwindcss.com "></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css ">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg fixed h-full top-0 left-0 z-20 overflow-y-auto">
            @include('partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-6 overflow-y-auto relative">

            <!-- Contenedor para mensajes -->
            <div class="relative z-20">
                <!-- Mensaje de éxito -->
                @if (session('success'))
                    <div id="alert-success"
                        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-96 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md opacity-0 translate-y-10 scale-95 transition-all duration-700 ease-out pointer-events-none">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button onclick="closeAlertSmooth('alert-success')"
                                class="text-green-700 hover:text-green-900 focus:outline-none ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const alert = document.getElementById('alert-success');
                            setTimeout(() => {
                                alert.classList.remove('opacity-0', 'translate-y-10', 'scale-95');
                                alert.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                                alert.classList.remove('pointer-events-none');
                            }, 100);

                            setTimeout(() => {
                                closeAlertSmooth('alert-success');
                            }, 3000);
                        });

                        function closeAlertSmooth(id) {
                            const alert = document.getElementById(id);
                            alert.classList.add('opacity-0', 'translate-y-10', 'scale-95');
                            alert.classList.remove('pointer-events-none');

                            setTimeout(() => {
                                if (alert) alert.remove();
                            }, 300);
                        }
                    </script>
                @endif

                <!-- Mensaje de advertencia -->
                @if (session('warning'))
                    <div id="alert-warning"
                        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-96 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg shadow-md opacity-0 translate-y-10 scale-95 transition-all duration-700 ease-out pointer-events-none">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span>{{ session('warning') }}</span>
                            </div>
                            <button onclick="closeAlertSmooth('alert-warning')"
                                class="text-yellow-700 hover:text-yellow-900 focus:outline-none ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const alert = document.getElementById('alert-warning');
                            setTimeout(() => {
                                alert.classList.remove('opacity-0', 'translate-y-10', 'scale-95');
                                alert.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                                alert.classList.remove('pointer-events-none');
                            }, 100);

                            setTimeout(() => {
                                closeAlertSmooth('alert-warning');
                            }, 3000);
                        });
                    </script>
                @endif
            </div>

            <!-- Contenido dinámico -->
            @yield('content')
        </main>
    </div>

    <!-- Footer estático al final -->
    <footer class="text-black p-4 z-10 mt-auto shadow-md ">
        <div class="text-center">
            <p class="text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnológica</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
