<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Contenedor Principal -->
    <div class="flex flex-1">

        <!-- Sidebar fijo -->
        <aside class="w-64 bg-white shadow-lg fixed h-full top-0 left-0 z-20 overflow-y-auto">
            @include('partials.sidebar')
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 ml-64 p-6 overflow-y-auto pb-24">
            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div id="success-alert"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="block sm:inline">{{ session('success') }}</strong>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <button onclick="document.getElementById('success-alert').style.display = 'none';">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.815l-2.651 3.034a1.2 1.2 0 0 1-1.705-1.705l2.758-3.15-2.759-3.152a1.2 1.2 0 0 1 1.705-1.705L10 8.183l2.651-3.031a1.2 1.2 0 0 1 1.697 1.705l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.706z" />
                            </svg>
                        </button>
                    </span>
                </div>
            @endif

            <!-- Contenido dinámico -->
            @yield('content')
        </main>

    </div>

    <!-- Footer estático al final -->
    <footer class=" text-black p-4 z-10 mt-6">
        <div class="text-center">
            <p class="text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnológica</p>
        </div>
    </footer>

    @stack('scripts')

</body>

</html>
