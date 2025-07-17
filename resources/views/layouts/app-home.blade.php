<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KrhonosVision')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css ">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">


    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64  shadow-lg fixed h-full top-0 left-0 z-20 overflow-y-auto">
            @include('partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main id="app" class="flex-1 ml-64 p-6 overflow-y-auto relative">

            <!-- Contenedor para mensajes de advertencia -->
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
            </div>

            <!-- Loader -->
            <div id="loading-spinner"
                class="absolute inset-0 bg-black-opaco flex items-center justify-center z-50 hidden">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
                    <p class="mt-4 text-white text-lg">Cargando...</p>
                </div>
            </div>

            <!-- Contenido dinámico -->
            @yield('content')
        </main>
    </div>


    <script>
        // Función para mostrar el spinner
        function showLoader() {
            document.getElementById('loading-spinner').classList.remove('hidden');
        }

        // Función para ocultar el spinner
        function hideLoader() {
            document.getElementById('loading-spinner').classList.add('hidden');
        }

        // Cargar contenido dinámico
        async function loadContent(url) {
            showLoader();
            try {
                const response = await fetch(url);
                const text = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, "text/html");
                const newContent = doc.querySelector("main").innerHTML;

                await new Promise(resolve => setTimeout(resolve, 200));
                document.getElementById("app").innerHTML = newContent;
                history.pushState(null, '', url);

                // Detectar si es la vista home desde la URL
                const isHome = url.includes('/home') || url.includes('/home/');

                // Iniciar actualización solo si es home
                if (isHome) {
                    if (!window.statusInterval) {
                        updateDeviceStatus(); // Primera carga
                        window.statusInterval = setInterval(updateDeviceStatus, 15000); // Cada 15 segundos
                    }
                } else {
                    if (window.statusInterval) {
                        clearInterval(window.statusInterval);
                        window.statusInterval = null;
                    }
                }

                // Iniciar scripts dinámicos
                initDynamicScripts();
                initScripts();

            } catch (error) {
                console.error("Error al cargar el contenido:", error);
                document.getElementById("app").innerHTML =
                    `<div class="p-4 text-red-600">Error al cargar el contenido.</div>`;
            } finally {
                hideLoader();
            }
        }


        // Detectar navegación del usuario (botón "Volver")
        window.addEventListener('popstate', async function(event) {
            if (event.state && event.state.path) {
                await loadContent(event.state.path);
            } else {
                location.reload(); // Fallback
            }
        });

        // para capturar el click
        document.body.addEventListener("click", function(e) {
            const link = e.target.closest("a");
            if (link && link.href) {
                const url = link.getAttribute("href");

                if (link.target ===
                    "_blank") { // Permitir que el navegador maneje la navegación normalmente (descargas)
                    return;
                }
                e.preventDefault();
                loadContent(url);
            }

        });

        /* para numero de HDD */
        function generateHDDForm(count) {
            const container = document.getElementById('hdd-fields');
            count = parseInt(count);
            if (isNaN(count) || count <= 0) return;

            // Limpiar contenido anterior
            container.innerHTML = '';

            // Recuperar datos anteriores si existen
            const existingData = @json(old('volumen'));

            for (let i = 0; i < count; i++) {
                const data = existingData?.[i] || {};
                const index = i;
                const number = i + 1;

                const html = `
            <div class="bg-gray-700 p-4 rounded-md border border-gray-600 mb-4">
                <h4 class="text-sm font-medium text-white mb-2">Volumen #${number}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Serial del disco -->
                    <div>
                        <label class="block text-sm font-medium text-white">Serial</label>
                        <input type="text" name="volumen[${index}][serial_disco]" 
                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" minlength="4">
                        <span class="text-red-400 text-sm mt-1 hidden">Este campo es obligatorio</span>
                    </div>
                    <!-- Capacidad del disco -->
                    <div>
                        <label class="block text-sm font-medium text-white">Capacidad/Disco (TB)</label>
                        <input type="number" name="volumen[${index}][capacidad_disco]"
                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1">
                        <span class="text-red-400 text-sm mt-1 hidden">Este campo es obligatorio</span>
                    </div>
                    <!-- Capacidad máxima del volumen -->
                    <div>
                        <label class="block text-sm font-medium text-white">Capacidad Máxima/volumen (TB)</label>
                        <input type="number" name="volumen[${index}][capacidad_max_volumen]"
                            class="mt-1 block w-full rounded-md bg-gray-600 border border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" required>
                        <span class="text-red-400 text-sm mt-1 hidden">Este campo es obligatorio</span>
                    </div>
                </div>
            </div>
        `;
                container.insertAdjacentHTML('beforeend', html);
            }

            // Restaurar valores anteriores (si existen)
            if (existingData) {
                for (let i = 0; i < existingData.length; i++) {
                    const vol = existingData[i];
                    document.querySelector(`input[name='volumen[${i}][serial_disco]']`).value = vol.serial_disco || '';
                    document.querySelector(`input[name='volumen[${i}][capacidad_disco]']`).value = vol
                        .capacidad_disco ||
                        '';
                    document.querySelector(`input[name='volumen[${i}][capacidad_max_volumen]']`).value = vol
                        .capacidad_max_volumen || '';
                }
            }
        }

        // Inicializar scripts modal para eliminacion
        function initScripts() {
            window.deleteUrl = '';

            window.openDeleteModal = function(url) {
                deleteUrl = url;
                document.getElementById('deleteModal')?.classList.remove('hidden');
                const reasonInput = document.getElementById('reason');
                if (reasonInput) reasonInput.value = '';
            };

            window.closeDeleteModal = function() {
                document.getElementById('deleteModal')?.classList.add('hidden');
            };

            window.submitDeleteForm = function() {
                const reason = document.getElementById('reason')?.value.trim();

                if (!reason) {
                    alert("Por favor, describa un motivo para eliminar.");
                    return;
                }

                const form = document.getElementById('deleteForm');
                if (form) {
                    form.action = deleteUrl;
                    document.getElementById('deletionReasonInput').value = reason;
                    form.submit();
                }
            };
        }

        // para para personalizar
        function initDynamicScripts() {

            //personaliza marca
            if (document.getElementById('mark')) {

                const select = document.getElementById('mark');
                const otherField = document.getElementById('other-brand-field');


                if (select && otherField) {
                    select.addEventListener('change', function() {
                        otherField.classList.toggle('hidden', select.value !== 'Otra');
                    });

                    if (select.value === 'Otra') {
                        otherField.classList.remove('hidden');
                    }
                }
            }

            //personaliza condicion 
            if (document.getElementById('name')) {

                const select = document.getElementById('name');
                const otherField = document.getElementById('other-condition-field');

                if (select && otherField) {
                    select.addEventListener('change', function() {
                        otherField.classList.toggle('hidden', select.value !== 'OTRO');
                    });

                    if (select.value === 'OTRO') {
                        otherField.classList.remove('hidden');
                    }
                }
            }

            //para eliminar nvr con camaras conectadas
            window.submit = function() {
                alert("El Nvr a eliminar tiene cámaras conectadas.");
                return false;
            };

        }

        //para validar filtros
        function validateFilters(type) {

            switch (type) {
                case 'atencion':
                    if (!document.querySelector("select[name='name']")?.value.trim()) {
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }
                    return true;
                case 'stock':
                    if (!document.querySelector("select[name='mark']")?.value.trim() && !document.querySelector(
                            "input[name='delivery_note']")?.value.trim()) {
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }
                    return true;
                case 'switch':
                    if (!document.querySelector("input[name='serial']")?.value.trim() && !document.querySelector(
                            "input[name='location']")?.value.trim()) {
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }
                    return true;
                case 'camera':
                    if (!document.querySelector("input[name='location']")?.value.trim() && !document.querySelector(
                            "select[name='status']")?.value.trim()) {
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }
                    return true;
                case 'nvr':
                    if (!document.querySelector("input[name='location']")?.value.trim() && !document.querySelector(
                            "select[name='status']")?.value.trim()) {
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }
                    return true;
                case 'link':
                    if (!document.querySelector("input[name='location']")?.value.trim()) {
                        alert('Por favor, ingresa al menos un valor para filtrar.');
                        return false;
                    }
                    return true;
                default:
                    return true;
            }

        }
    </script>

    {{-- captura que  este en la vista home  --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Verificar si la URL actual es home
            const currentUrl = window.location.pathname;
            if (currentUrl === '/home' || currentUrl === '/home/') {
                updateDeviceStatus(); // Primera carga
                window.statusInterval = setInterval(updateDeviceStatus, 15000); // Cada 15 segundos
            }
        });
    </script>

    {{-- actualización de tabla en vista inicial  --}}
    <script>
        let statusInterval = null;

        function updateDeviceStatus() {
            fetch("{{ route('test.check') }}")
                .then(response => response.json())
                .then(data => {
                    updateTable('camera-status-table', data.cameras, 'camera');
                    updateTable('nvr-status-table', data.nvrs, 'nvr');
                })
                .catch(error => {
                    console.error('Error al actualizar estados:', error);
                    showError('camera-status-table', 'Error en cámaras');
                    showError('nvr-status-table', 'Error en Nvr');
                });
        }

        function updateTable(tableId, devices, type) {
            const tableBody = document.getElementById(tableId);
            if (!tableBody) return;

            tableBody.innerHTML = '';
            if (devices.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                    No hay equipos inactivos
                </td>
            `;
                tableBody.appendChild(row);
                return;
            }

            devices.forEach(device => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-900 transition-colors duration-150';

                if (type === 'camera') {
                    row.innerHTML = `
                    <td class="px-6 py-4 text-center text-sm text-white">${device.mac}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.nvr || '-'}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.name}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.location}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.ip}</td>
                    <td class="px-6 py-4 text-center text-sm">
                        <span id="status-${device.mac}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${device.status === 'offline' ? 'bg-red-600 text-red-100' : 'bg-yellow-600 text-yellow-100'}">
                            ${device.status}
                        </span>
                    </td>
                `;
                } else {
                    row.innerHTML = `
                    <td class="px-6 py-4 text-center text-sm text-white">${device.mac}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.name}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.location}</td>
                    <td class="px-6 py-4 text-center text-sm text-white">${device.ip}</td>
                    <td class="px-6 py-4 text-center text-sm">
                        <span id="nvr-status-${device.mac}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${device.status === 'offline' ? 'bg-red-600 text-red-100' : 'bg-yellow-600 text-yellow-100'}">
                            ${device.status}
                        </span>
                    </td>
                `;
                }

                tableBody.appendChild(row);
            });
        }

        function showError(tableId, message) {
            const tableBody = document.getElementById(tableId);
            if (!tableBody) return;

            tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">
                    ${message}
                </td>
            </tr>
        `;
        }
    </script>

    @stack('scripts')

    <!-- Footer estático al final -->
    <footer>
    </footer>

</body>

</html>
