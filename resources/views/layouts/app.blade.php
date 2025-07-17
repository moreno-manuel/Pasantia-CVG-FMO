<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','KrhonosVision')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">

    <!-- Header -->
    <header class="bg-gray-800 text-white p-4 flex items-center justify-between">

        <!-- Logo -->
        <div class="absolute left-4 top-4">
            <img src="{{ asset('images/logo_view.png') }}" alt="Logo" class="h-8 w-auto">
        </div>

        <h1 class="text-2xl font-bold text-center w-full">KrhonosVision</h1>

        <!-- Logo -->
        <div class="absolute right-4 top-4">
            <img src="{{ asset('images/spartan.png') }}" alt="Logo" class="h-10 w-auto">
        </div>
    </header>


    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4">
        <p class="text-center text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnologica </p>
    </footer>

</body>

</html>
