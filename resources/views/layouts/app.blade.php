<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com "></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    @livewireStyles
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">

    <!-- Header -->
    <header class="bg-gray-800 text-white p-4">
        <h1 class="text-2xl font-bold text-center">KrhonosVision</h1>
    </header>

    @yield('content')

    @livewireScripts

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4">
        <p class="text-center text-sm">© {{ date('Y') }}. CVG Ferrominera. Seguridad Tecnologica </p>
    </footer>

</body>

</html>
