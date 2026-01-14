<?php
// Proteger la página - solo administradores (rol = 1)
require_once __DIR__ . '/auth_middleware.php';
verificarRol([1]); // Solo administradores

include __DIR__ . '/components/aside.php';
?>

<main class="min-h-screen p-0 md:p-6">
    <div class="w-full mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Insignias Apolo</h1>
                <p class="text-gray-500 text-sm mt-1">Administra los socios galardonados con la insignia Modesto Apolo
                </p>
            </div>
            <button onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo socio
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" id="filtro-nombre" placeholder="Buscar por nombre..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                <div class="flex-1">
                    <input type="text" id="filtro-anio" placeholder="Buscar por año..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                <button onclick="limpiarFiltros()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Tabla de socios -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cargo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Año</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Logros</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="socios-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay socios registrados</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza registrando un nuevo socio galardonado.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto">
    <form id="formSocio" class="p-6 space-y-4">
        <input type="hidden" id="socio_id" name="id">

        <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Nuevo socio</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nombre" name="nombre" required maxlength="255"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: Arq. María Fernanda Torres" />
            </div>
            <div>
                <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo <span class="text-red-500">*</span>
                </label>
                <input type="text" id="cargo" name="cargo" required maxlength="255"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: Gerente de Proyectos" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="anio" class="block text-sm font-medium text-gray-700 mb-2">
                    Año del Galardón
                </label>
                <input type="text" id="anio" name="anio" maxlength="10"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: 2024" value="<?= date('Y') ?>" />
            </div>
        </div>

        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                Foto del socio
            </label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, WEBP. Tamaño máximo: 5MB</p>
            <input type="hidden" id="url_img" name="url_img">
            <div id="preview-foto" class="mt-3 hidden">
                <p class="text-xs text-gray-600 mb-1">Vista previa:</p>
                <img id="preview-img" src="" alt="Vista previa" class="max-h-40 rounded-lg border shadow-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Logros y Reconocimientos
            </label>
            <div id="logros-container" class="space-y-2">
                <!-- Inputs dinámicos -->
            </div>
            <button type="button" onclick="agregarLogro()"
                class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar logro
            </button>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
            <button type="button" onclick="document.getElementById('modalForm').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                Cancelar
            </button>

            <button type="submit"
                class="px-4 py-2 bg-[var(--tj-color-theme-primary)] hover:opacity-90 text-white rounded-lg font-medium transition">
                Guardar
            </button>
        </div>
    </form>
</dialog>

<!-- MODAL ELIMINAR -->
<dialog id="modalDelete" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-md
           fixed inset-0 m-auto shadow-xl border-0">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Eliminar socio</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar al socio <strong id="delete-socio-nombre"></strong>?
            Esta acción no se puede deshacer.
        </p>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
            <button type="button" onclick="document.getElementById('modalDelete').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                Cancelar
            </button>

            <button type="button" onclick="confirmDelete()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                Eliminar
            </button>
        </div>
    </div>
</dialog>

<!-- Toast notifications -->
<div id="toast"
    class="hidden fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 transition-all">
    <div class="flex items-center gap-3">
        <div id="toast-icon"></div>
        <div class="flex-1">
            <p id="toast-message" class="text-sm font-medium text-gray-900"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
    // Estado global
    let deleteId = null;
    let todosSocios = [];
    let sortable = null; // Instancia de SortableJS

    // Cargar socios al iniciar
    document.addEventListener('DOMContentLoaded', function () {
        loadSocios();

        // Filtros en tiempo real
        document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
        document.getElementById('filtro-anio').addEventListener('input', aplicarFiltros);

        // Vista previa de imagen
        document.getElementById('foto').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                previewImage(file);
            }
        });
    });

    // Cargar socios desde el servidor
    async function loadSocios() {
        try {
            const response = await fetch('api/socios_apolo/listar.php');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                todosSocios = data.data;
                aplicarFiltros();
            } else {
                todosSocios = [];
                mostrarEstadoVacio();
            }
        } catch (error) {
            showToast('Error al cargar los socios', 'error');
            console.error('Error:', error);
        }
    }

    // Aplicar filtros
    function aplicarFiltros() {
        const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
        const filtroAnio = document.getElementById('filtro-anio').value.toLowerCase();

        const sociosFiltrados = todosSocios.filter(socio => {
            const nombreCompleto = socio.nombre.toLowerCase();
            const coincideNombre = nombreCompleto.includes(filtroNombre);
            const coincideAnio = !socio.anio || socio.anio.toLowerCase().includes(filtroAnio);
            return coincideNombre && coincideAnio;
        });

        renderSocios(sociosFiltrados);
    }

    // Inicializar SortableJS
    function initSortable() {
        const tbody = document.getElementById('socios-tbody');

        if (sortable) {
            sortable.destroy();
        }

        sortable = new Sortable(tbody, {
            animation: 150,
            handle: '.drag-handle', // Clase del manejador
            ghostClass: 'bg-blue-50', // Clase para el elemento fantasma
            onEnd: function (evt) {
                // Obtener el nuevo orden
                const newOrder = [];
                tbody.querySelectorAll('tr').forEach(row => {
                    newOrder.push(row.getAttribute('data-id'));
                });

                // Guardar el nuevo orden
                saveOrder(newOrder);
            }
        });
    }

    // Guardar el nuevo orden en el servidor
    async function saveOrder(order) {
        try {
            const response = await fetch('api/socios_apolo/reordenar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ orden: order })
            });

            const result = await response.json();

            if (result.success) {
                showToast('Orden actualizado correctamente', 'success');
            } else {
                showToast('Error al actualizar el orden', 'error');
                console.error('Error:', result);
                // Recargar para revertir cambios visuales si falló
                loadSocios();
            }
        } catch (error) {
            showToast('Error de conexión al guardar orden', 'error');
            console.error('Error:', error);
            loadSocios();
        }
    }

    // Renderizar socios en la tabla
    function renderSocios(socios) {
        const tbody = document.getElementById('socios-tbody');
        const emptyState = document.getElementById('empty-state');

        if (socios.length > 0) {
            tbody.innerHTML = socios.map(socio => {
                const logrosHtml = Array.isArray(socio.logros)
                    ? socio.logros.map(l => `<span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs text-gray-600 mr-1 mb-1">${escapeHtml(l)}</span>`).join('')
                    : '';

                return `
            <tr class="hover:bg-gray-50 transition bg-white border-b" data-id="${socio.id}">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 cursor-move drag-handle">
                    <svg class="w-6 h-6 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${socio.imagen ?
                        `<img src="../${escapeHtml(socio.imagen)}" alt="${escapeHtml(socio.nombre)}" class="w-10 h-10 rounded-full object-cover" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23ccc%27%3E%3Cpath d=%27M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z%27/%3E%3C/svg%3E';">` :
                        `<div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>`
                    }
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${escapeHtml(socio.nombre)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-500">${escapeHtml(socio.cargo)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        ${escapeHtml(socio.anio)}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-wrap max-w-xs">
                        ${logrosHtml}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                        onclick='openEditModal(${JSON.stringify(socio).replace(/'/g, "&apos;")})'
                        class="text-blue-600 hover:text-blue-900 mr-3"
                        title="Editar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        onclick="openDeleteModal(${socio.id}, '${escapeHtml(socio.nombre)}')"
                        class="text-red-600 hover:text-red-900"
                        title="Eliminar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
            `;
            }).join('');
            emptyState.classList.add('hidden');

            // Inicializar Sortable después de renderizar
            // Solo si no hay filtros activos
            const filtroNombre = document.getElementById('filtro-nombre').value;
            const filtroAnio = document.getElementById('filtro-anio').value;

            if (!filtroNombre && !filtroAnio) {
                initSortable();
            } else if (sortable) {
                sortable.destroy();
                sortable = null;
            }
        } else {
            mostrarEstadoVacio();
        }
    }

    function mostrarEstadoVacio() {
        const tbody = document.getElementById('socios-tbody');
        const emptyState = document.getElementById('empty-state');
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
    }

    function limpiarFiltros() {
        document.getElementById('filtro-nombre').value = '';
        document.getElementById('filtro-anio').value = '';
        aplicarFiltros();
    }

    // Gestión de logros dinámicos
    function agregarLogro(valor = '') {
        const container = document.getElementById('logros-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
        <input type="text" name="logros[]" value="${escapeHtml(valor)}" class="flex-1 px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ej: Excelencia en Ventas">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
        container.appendChild(div);
    }

    function openCreateModal() {
        document.getElementById('socio_id').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('cargo').value = '';
        document.getElementById('anio').value = new Date().getFullYear();
        document.getElementById('foto').value = '';
        document.getElementById('url_img').value = '';
        document.getElementById('logros-container').innerHTML = '';
        agregarLogro(); // Agregar un campo vacío por defecto

        document.getElementById('preview-foto').classList.add('hidden');
        document.getElementById('modal-title').textContent = 'Nuevo socio';
        document.getElementById('modalForm').showModal();
    }

    function openEditModal(socio) {
        document.getElementById('socio_id').value = socio.id;
        document.getElementById('nombre').value = socio.nombre;
        document.getElementById('cargo').value = socio.cargo;
        document.getElementById('anio').value = socio.anio || '';
        document.getElementById('foto').value = '';
        document.getElementById('url_img').value = socio.imagen || '';

        // Cargar logros
        const container = document.getElementById('logros-container');
        container.innerHTML = '';
        if (Array.isArray(socio.logros) && socio.logros.length > 0) {
            socio.logros.forEach(logro => agregarLogro(logro));
        } else {
            agregarLogro();
        }

        if (socio.imagen) {
            document.getElementById('preview-img').src = '../' + socio.imagen;
            document.getElementById('preview-foto').classList.remove('hidden');
        } else {
            document.getElementById('preview-foto').classList.add('hidden');
        }

        document.getElementById('modal-title').textContent = 'Editar socio';
        document.getElementById('modalForm').showModal();
    }

    function openDeleteModal(id, nombre) {
        deleteId = id;
        document.getElementById('delete-socio-nombre').textContent = nombre;
        document.getElementById('modalDelete').showModal();
    }

    document.getElementById('formSocio').addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('socio_id').value;
        const nombre = document.getElementById('nombre').value.trim();
        const cargo = document.getElementById('cargo').value.trim();
        const anio = document.getElementById('anio').value.trim();
        const fotoFile = document.getElementById('foto').files[0];
        let url_img = document.getElementById('url_img').value.trim();

        // Recoger logros
        const logrosInputs = document.querySelectorAll('input[name="logros[]"]');
        const logros = Array.from(logrosInputs).map(input => input.value.trim()).filter(val => val !== '');

        if (!nombre || !cargo) {
            showToast('Por favor completa los campos obligatorios', 'error');
            return;
        }

        try {
            if (fotoFile) {
                const formData = new FormData();
                formData.append('imagen', fotoFile);

                const uploadResponse = await fetch('api/socios_apolo/subir-imagen.php', {
                    method: 'POST',
                    body: formData
                });

                const uploadResult = await uploadResponse.json();

                if (uploadResult.success) {
                    url_img = uploadResult.data.url;
                } else {
                    showToast(uploadResult.message || 'Error al subir la imagen', 'error');
                    return;
                }
            }

            const url = id ? 'api/socios_apolo/editar.php' : 'api/socios_apolo/crear.php';
            const data = {
                id: id || undefined,
                nombre,
                cargo,
                anio,
                imagen: url_img,
                logros
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Operación exitosa', 'success');
                document.getElementById('modalForm').close();
                loadSocios();
            } else {
                showToast(result.message || 'Error en la operación', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
            console.error('Error:', error);
        }
    });

    async function confirmDelete() {
        if (!deleteId) return;

        try {
            const response = await fetch('api/socios_apolo/eliminar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: deleteId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Socio eliminado', 'success');
                document.getElementById('modalDelete').close();
                loadSocios();
            } else {
                showToast(result.message || 'Error al eliminar', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
            console.error('Error:', error);
        }

        deleteId = null;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');

        toastMessage.textContent = message;

        if (type === 'success') {
            toast.className = 'fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 border-green-500 transition-all';
            toastIcon.innerHTML = `
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        `;
        } else {
            toast.className = 'fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 border-red-500 transition-all';
            toastIcon.innerHTML = `
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        `;
        }

        toast.classList.remove('hidden');

        setTimeout(() => {
            hideToast();
        }, 4000);
    }

    function hideToast() {
        document.getElementById('toast').classList.add('hidden');
    }

    function previewImage(file) {
        const preview = document.getElementById('preview-foto');
        const previewImg = document.getElementById('preview-img');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<?php include __DIR__ . '/components/footer.php'; ?>