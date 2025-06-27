<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">

    <!-- Header -->
    <header class="bg-gray-800 text-white p-4">
        <h1 class="text-2xl font-bold text-center">KrhonosVision</h1>
    </header>


    <!-- Contenedor para posicionamiento -->
    <div class="relative">
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
                // Mostrar alerta con animación
                document.addEventListener("DOMContentLoaded", function() {
                    const alert = document.getElementById('alert-success');
                    setTimeout(() => {
                        alert.classList.remove('opacity-0', 'translate-y-10', 'scale-95');
                        alert.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                        alert.classList.remove('pointer-events-none');
                    }, 100);

                    // Ocultar automáticamente después de 3 segundos
                    setTimeout(() => {
                        closeAlertSmooth('alert-success');
                    }, 3000);
                });

                // Función reutilizable para cerrar con animación
                function closeAlertSmooth(id) {
                    const alert = document.getElementById(id);
                    alert.classList.add('opacity-0', 'translate-y-10', 'scale-95');
                    alert.classList.remove('pointer-events-none');

                    // Eliminar del DOM después de que termine la animación
                    setTimeout(() => {
                        if (alert) alert.remove();
                    }, 300); // Duración de la animación de salida
                }
            </script>
        @endif
    </div>


    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4">
        <p class="text-center text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnologica </p>
    </footer>

</body>

</html>
