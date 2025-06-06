@extends('layouts.app-home')
@section('content')
    <!-- resources/views/front/person-user/index.blade.php -->


    <div class="container mx-auto px-4 py-6">

        <!-- Encabezado y botón agregar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Usuarios</h1>
            <a href="{{ route('usuarios.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Agregar Nuevo Usuario
            </a>
        </div>

        <!-- Filtros para búsqueda -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return validateFilters()">

            <!-- Localidad -->
            <div>
                <label class="block text-sm font-medium text-black mb-1">Nombre de Persona</label>
                <input type="text" name="nombre_person" value="{{ $filters['nombre_person'] ?? '' }}"
                    class="w-full rounded-md border-black shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-black mb-1">Rol</label>
                <select name="rol"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos</option>
                    <option value="admin">admin</option>
                    <option value="editor">editor</option>
                    <option value="lector">lector</option>
                </select>
            </div>

            <!-- Botones: Filtrar + Limpiar -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-1 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs">
                    Filtrar
                </button>

                <!-- Botón Limpiar Filtros -->
                <a href="#" class="px-1 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-xs">
                    Limpiar
                </a>
            </div>
        </form>

        <br>

        {{-- valida para mostrar tabla de personas o mensaje --}}
        @if ($persons->isNotEmpty())
            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full shadow-md rounded-lg overflow-hidden bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-800 divide-x divide-blue-400">
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Ficha</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Nombre</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Apellido</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Genero</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Usuario</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Rol</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($persons as $person)
                            @php
                                $user = $person->user;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-900 truncate">{{ $person['license'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person['name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person['last_name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $person['sex'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $user->userName }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $user->rol }}</td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="#"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver
                                        </a>
                                        <a href="#"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            Editar
                                        </a>
                                        <form action="#" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- paginacion --}}
            {{--  {{ $persons->appends([
                    'location' => $filters['location'] ?? '',
                    'status' => $filters['status'] ?? '',
                ])->links() }} --}}
        @else
            <div class="text-center mt-6 bg-gray-800 border border-black rounded-md p-4 text-white">
                <p>No hay registros existentes</p>
            </div>
        @endif


    </div>

    {{-- funcion para los filtros --}}
    {{--  @push('scripts')
        <script>
            function validateFilters() {
                // Obtén los valores de los campos de filtro
                const location = document.querySelector("input[name='location']")?.value.trim();
                const status = document.querySelector("select[name='status']")?.value.trim();

                // Verifica si estávacíos
                if (!location && !status) {
                    // Cancelar envío del formulario
                    alert('Por favor, ingresa al menos un valor para filtrar.');
                    return false;
                }
                // Si hay un valor
                return true;
            }
        </script>
    @endpush
 --}}


@endsection
