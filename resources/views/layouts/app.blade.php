<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KrhonosVision')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">

    <header class="bg-red-900 text-white p-4 flex items-center justify-center relative h-24">
        <!-- Logo -->
        <div class="absolute left-4 top-1/2 -translate-y-1/2">
            <img src="{{ asset('images/logo_ferro.png') }}" alt="Logo" class="h-24 w-auto object-contain">
        </div>

        <h1 class="text-2xl font-bold">KrhonosVision</h1>

        <!-- Logo -->
        <div class="absolute right-4 top-1/2 -translate-y-1/2">
            <img src="{{ asset('images/logo_cvg.png') }}" alt="Logo" class="h-20 w-auto object-contain">
        </div>

    </header>


    @yield('content')

    <!-- Footer -->
    <footer class="bg-red-900 text-white p-4">
        <p class="text-center text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnologica </p>
    </footer>

</body>

</html>
