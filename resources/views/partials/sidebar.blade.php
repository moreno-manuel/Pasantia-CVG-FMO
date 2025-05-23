<nav class="mt-10">
    <ul class="space-y-2">
        <!-- Inicio -->
        <li>
            <a href="{{route('home')}}" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 rounded">Inicio</a>
        </li>

        <!-- Menú desplegable con <Equipos> -->
        <li>
            <details class="group">
                <summary
                    class="flex justify-between items-center px-6 py-3 text-gray-700 hover:bg-blue-50 rounded cursor-pointer list-none group-open:ring-2 group-open:ring-blue-300">
                    Equipos
                    <svg class="w-4 h-4 transition-transform transform group-open:rotate-180" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <ul class="mt-1 ml-4 space-y-1">
                    <li><a href="{{route('camara.index')}}" class="block px-4 py-2 text-gray-600 hover:bg-blue-100 rounded">Camara</a>
                    </li>
                    <li><a href="{{route ('nvr.index')}}" class="block px-4 py-2 text-gray-600 hover:bg-blue-100 rounded">NVR</a></li>
                    <li><a href="{{route('switch.index')}}" class="block px-4 py-2 text-gray-600 hover:bg-blue-100 rounded">Switch</a>
                    </li>
                    <li><a href="{{route('link.index')}}" class="block px-4 py-2 text-gray-600 hover:bg-blue-100 rounded">Enlace</a>
                    </li>

                </ul>
            </details>
        </li>

        <!-- historial equipos eliminados -->
        <li>
            <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 rounded">Equipos Eliminados</a>

        </li>

        <!-- Configuraciones-->
        <li>
            <details class="group">
                <summary
                    class="flex justify-between items-center px-6 py-3 text-gray-700 hover:bg-blue-50 rounded cursor-pointer list-none group-open:ring-2 group-open:ring-blue-300">
                    Configuración
                    <svg class="w-4 h-4 transition-transform transform group-open:rotate-180" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <ul class="mt-1 ml-4 space-y-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-100 rounded">Perfil</a>
                    </li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-100 rounded">Crear Nuevo
                            Usuario</a></li>
                </ul>
            </details>
        </li>

        <!-- Botón de Salir -->
        <li class="absolute bottom-0 w-full">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left px-6 py-3 text-red-600 hover:bg-red-50 rounded transition-colors duration-200">
                    Salir
                </button>
            </form>
        </li>
    </ul>
</nav>
