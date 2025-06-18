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
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg fixed h-full top-0 left-0 z-20 overflow-y-auto">
            @include('partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-6 overflow-y-auto">
            <!-- Contenido dinámico -->
            @yield('content')
        </main>
    </div>

    <!-- Footer estático al final -->
    <footer class="bg-white text-black p-4 z-10 mt-auto shadow-md">
        <div class="text-center">
            <p class="text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnológica</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
