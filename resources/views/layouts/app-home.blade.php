<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body>

    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg fixed h-full">
        @include('partials.sidebar')
    </div>

    <!-- Contenido dinámico-->
    <div class="flex-1 ml-64 p-6 overflow-y-auto">
        @yield('content')
    </div>

    <footer></footer>
</body>
