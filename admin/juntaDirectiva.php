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
                <h1 class="text-2xl font-bold text-gray-900">Junta Directiva</h1>
                <p class="text-gray-500 text-sm mt-1">Administra los miembros de la junta directiva</p>
            </div>
            <button onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo miembro
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" id="filtro-nombre" placeholder="Buscar por nombre o apellido..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                <div class="flex-1">
                    <input type="text" id="filtro-categoria" placeholder="Buscar por categoría..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                <div class="flex-1">
                    <input type="text" id="filtro-rol" placeholder="Buscar por rol..."
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

        <!-- Tabla de miembros -->
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
                                Nombre Completo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha creación</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="miembros-tbody" class="bg-white divide-y divide-gray-200">
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay miembros</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo miembro de la junta directiva.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto">
    <form id="formMiembro" class="p-6 space-y-4">
        <input type="hidden" id="miembro_id" name="id">

        <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Nuevo miembro</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nombre" name="nombre" required maxlength="100"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: Juan" />
            </div>

            <div>
                <label for="apellido" class="block text-sm font-medium text-gray-700 mb-2">
                    Apellido <span class="text-red-500">*</span>
                </label>
                <input type="text" id="apellido" name="apellido" required maxlength="100"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: Pérez" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select id="categoria" name="categoria" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Seleccione una categoría</option>
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>

            <div>
                <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                    Rol <span class="text-red-500">*</span>
                </label>
                <input type="text" id="rol" name="rol" required maxlength="150"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: Director General" />
            </div>
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                Descripción
            </label>
            <textarea id="descripcion" name="descripcion" rows="3"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Breve descripción del perfil profesional..."></textarea>
        </div>

        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                Foto del miembro
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                </label>
                <input type="tel" id="telefono" name="telefono"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: 0987654321" />
            </div>

            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo <span class="text-red-500">*</span>
                </label>
                <input type="email" id="correo" name="correo" required maxlength="150"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: juan@example.com" />
            </div>
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
                <h2 class="text-xl font-semibold text-gray-900">Eliminar miembro</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar a <strong id="delete-miembro-nombre"></strong>?
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
    let todosMiembros = []; // Guardar todos los miembros para filtrado
    let todasCategorias = []; // Guardar todas las categorías
    let sortable = null; // Instancia de SortableJS

    // Cargar miembros al iniciar
    document.addEventListener('DOMContentLoaded', function () {
        loadCategorias(); // Cargar categorías primero
        loadMiembros();

        // Filtros en tiempo real
        document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
        document.getElementById('filtro-categoria').addEventListener('input', aplicarFiltros);
        document.getElementById('filtro-rol').addEventListener('input', aplicarFiltros);

        // Vista previa de imagen
        document.getElementById('foto').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                previewImage(file);
            }
        });
    });

    // Inicializar SortableJS
    function initSortable() {
        const tbody = document.getElementById('miembros-tbody');

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
            const response = await fetch('api/juntaDirectiva/reordenar.php', {
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
                loadMiembros();
            }
        } catch (error) {
            showToast('Error de conexión al guardar orden', 'error');
            console.error('Error:', error);
            loadMiembros();
        }
    }

    // Cargar categorías desde el servidor
    async function loadCategorias() {
        try {
            const response = await fetch('api/categoriasJunta/listar.php');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                todasCategorias = data.data;
                populateCategoriaSelect();
            }
        } catch (error) {
            console.error('Error al cargar categorías:', error);
        }
    }

    // Poblar el select de categorías
    function populateCategoriaSelect() {
        const select = document.getElementById('categoria');
        // Limpiar opciones existentes excepto la primera
        while (select.options.length > 1) {
            select.remove(1);
        }

        // Agregar opciones de categorías
        todasCategorias.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.nombre;
            option.textContent = cat.nombre;
            select.appendChild(option);
        });
    }

    // Cargar miembros desde el servidor
    async function loadMiembros() {
        try {
            const response = await fetch('api/juntaDirectiva/listar.php');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                todosMiembros = data.data;
                aplicarFiltros();
            } else {
                todosMiembros = [];
                mostrarEstadoVacio();
            }
        } catch (error) {
            showToast('Error al cargar los miembros', 'error');
            console.error('Error:', error);
        }
    }

    // Aplicar filtros
    function aplicarFiltros() {
        const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
        const filtroCategoria = document.getElementById('filtro-categoria').value.toLowerCase();
        const filtroRol = document.getElementById('filtro-rol').value.toLowerCase();

        const miembrosFiltrados = todosMiembros.filter(miembro => {
            const nombreCompleto = (miembro.nombre + ' ' + miembro.apellido).toLowerCase();
            const coincideNombre = nombreCompleto.includes(filtroNombre);
            const coincideCategoria = !filtroCategoria || miembro.categoria.toLowerCase().includes(filtroCategoria);
            const coincideRol = !filtroRol || miembro.rol.toLowerCase().includes(filtroRol);
            return coincideNombre && coincideCategoria && coincideRol;
        });

        renderMiembros(miembrosFiltrados);
    }

    // Renderizar miembros en la tabla
    function renderMiembros(miembros) {
        const tbody = document.getElementById('miembros-tbody');
        const emptyState = document.getElementById('empty-state');

        if (miembros.length > 0) {
            tbody.innerHTML = miembros.map(miembro => `
            <tr class="hover:bg-gray-50 transition bg-white border-b" data-id="${miembro.id}">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 cursor-move drag-handle">
                    <svg class="w-6 h-6 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${miembro.url_img ?
                    `<img src="../${escapeHtml(miembro.url_img)}" alt="${escapeHtml(miembro.nombre)}" class="w-10 h-10 rounded-full object-cover" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23ccc%27%3E%3Cpath d=%27M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z%27/%3E%3C/svg%3E';">` :
                    `<div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>`
                }
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${escapeHtml(miembro.nombre)} ${escapeHtml(miembro.apellido)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-500">${escapeHtml(miembro.categoria)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-500">${escapeHtml(miembro.rol)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${miembro.telefono ? escapeHtml(miembro.telefono) : '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${escapeHtml(miembro.correo)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatDate(miembro.created_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                        onclick='openEditModal(${JSON.stringify(miembro).replace(/'/g, "&apos;")})'
                        class="text-blue-600 hover:text-blue-900 mr-3"
                        title="Editar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        onclick="openDeleteModal(${miembro.id}, '${escapeHtml(miembro.nombre)} ${escapeHtml(miembro.apellido)}')"
                        class="text-red-600 hover:text-red-900"
                        title="Eliminar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `).join('');
            emptyState.classList.add('hidden');

            // Inicializar Sortable después de renderizar
            // Solo si no hay filtros activos (para evitar reordenar una lista filtrada que podría ser confuso)
            const filtroNombre = document.getElementById('filtro-nombre').value;
            const filtroCategoria = document.getElementById('filtro-categoria').value;
            const filtroRol = document.getElementById('filtro-rol').value;

            if (!filtroNombre && !filtroCategoria && !filtroRol) {
                initSortable();
            } else if (sortable) {
                sortable.destroy(); // Desactivar si hay filtros
                sortable = null;
            }

        } else {
            mostrarEstadoVacio();
        }
    }

    // Mostrar estado vacío
    function mostrarEstadoVacio() {
        const tbody = document.getElementById('miembros-tbody');
        const emptyState = document.getElementById('empty-state');
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
    }

    // Limpiar filtros
    function limpiarFiltros() {
        document.getElementById('filtro-nombre').value = '';
        document.getElementById('filtro-categoria').value = '';
        document.getElementById('filtro-rol').value = '';
        aplicarFiltros();
    }

    // Abrir modal para crear
    function openCreateModal() {
        document.getElementById('miembro_id').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('apellido').value = '';
        document.getElementById('categoria').value = '';
        document.getElementById('rol').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('foto').value = '';
        document.getElementById('url_img').value = '';
        document.getElementById('telefono').value = '';
        document.getElementById('correo').value = '';
        document.getElementById('preview-foto').classList.add('hidden');
        document.getElementById('modal-title').textContent = 'Nuevo miembro';
        document.getElementById('modalForm').showModal();
    }

    // Abrir modal para editar
    function openEditModal(miembro) {
        document.getElementById('miembro_id').value = miembro.id;
        document.getElementById('nombre').value = miembro.nombre;
        document.getElementById('apellido').value = miembro.apellido;
        document.getElementById('categoria').value = miembro.categoria;
        document.getElementById('rol').value = miembro.rol;
        document.getElementById('descripcion').value = miembro.descripcion || '';
        document.getElementById('foto').value = '';
        document.getElementById('url_img').value = miembro.url_img || '';
        document.getElementById('telefono').value = miembro.telefono || '';
        document.getElementById('correo').value = miembro.correo;

        // Mostrar vista previa si hay foto
        if (miembro.url_img) {
            document.getElementById('preview-img').src = '../' + miembro.url_img;
            document.getElementById('preview-foto').classList.remove('hidden');
        } else {
            document.getElementById('preview-foto').classList.add('hidden');
        }

        document.getElementById('modal-title').textContent = 'Editar miembro';
        document.getElementById('modalForm').showModal();
    }

    // Abrir modal para eliminar
    function openDeleteModal(id, nombre) {
        deleteId = id;
        document.getElementById('delete-miembro-nombre').textContent = nombre;
        document.getElementById('modalDelete').showModal();
    }

    // Manejar submit del formulario
    document.getElementById('formMiembro').addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('miembro_id').value;
        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const categoria = document.getElementById('categoria').value.trim();
        const rol = document.getElementById('rol').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const fotoFile = document.getElementById('foto').files[0];
        let url_img = document.getElementById('url_img').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const correo = document.getElementById('correo').value.trim();

        if (!nombre || !apellido || !categoria || !rol || !correo) {
            showToast('Los campos nombre, apellido, categoría, rol y correo son obligatorios', 'error');
            return;
        }

        try {
            // Si hay un archivo nuevo, subirlo primero
            if (fotoFile) {
                const formData = new FormData();
                formData.append('imagen', fotoFile);

                const uploadResponse = await fetch('api/juntaDirectiva/subir-imagen.php', {
                    method: 'POST',
                    body: formData
                });

                const uploadResult = await uploadResponse.json();

                if (uploadResult.success) {
                    url_img = uploadResult.data.url;

                    // Mostrar debug info si existe (para desarrollo)
                    if (uploadResult.debug_info) {
                        console.debug('Upload Debug Info:', uploadResult.debug_info);
                    }
                } else {
                    console.error('Error al subir imagen:', uploadResult);
                    let errorMsg = uploadResult.message || 'Error al subir la imagen';

                    if (uploadResult.debug) {
                        console.error('Debug info:', uploadResult.debug);
                        errorMsg += '\n\nRevisa la consola del navegador para más detalles.';
                    }

                    showToast(errorMsg, 'error');
                    return;
                }
            }

            // Ahora guardar/actualizar el miembro
            const url = id ? 'api/juntaDirectiva/editar.php' : 'api/juntaDirectiva/crear.php';
            const data = {
                nombre,
                apellido,
                categoria,
                rol,
                descripcion,
                url_img: url_img || null,
                telefono: telefono || null,
                correo
            };

            if (id) {
                data.id = id;
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Operación exitosa', 'success');
                document.getElementById('modalForm').close();
                loadMiembros();
            } else {
                showToast(result.message || 'Error en la operación', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
            console.error('Error:', error);
        }
    });

    // Confirmar eliminación
    async function confirmDelete() {
        if (!deleteId) return;

        try {
            const response = await fetch('api/juntaDirectiva/eliminar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: deleteId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Miembro eliminado', 'success');
                document.getElementById('modalDelete').close();
                loadMiembros();
            } else {
                showToast(result.message || 'Error al eliminar', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
            console.error('Error:', error);
        }

        deleteId = null;
    }

    // Utilidades
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');

        toastMessage.textContent = message;

        // Configurar icono y color según el tipo
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

    // Vista previa de imagen
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