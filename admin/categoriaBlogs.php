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
                <h1 class="text-2xl font-bold text-gray-900">Categorías de Blog</h1>
                <p class="text-gray-500 text-sm mt-1">Administra las categorías para tus entradas de blog</p>
            </div>
            <button
                onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva categoría
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input
                        type="text"
                        id="filtro-nombre"
                        placeholder="Buscar por nombre..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                </div>
                <button
                    onclick="limpiarFiltros()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Tabla de categorías -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha creación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última actualización</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="categorias-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay categorías</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva categoría.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm"
    class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-md
           fixed inset-0 m-auto shadow-xl border-0"
>
    <form id="formCategoria" class="p-6 space-y-4">
        <input type="hidden" id="categoria_id" name="id">
        
        <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Nueva categoría</h2>

        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                Nombre de la categoría <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Ej: Tecnología, Negocios..."
            />
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
            <button
                type="button"
                onclick="document.getElementById('modalForm').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition"
            >
                Cancelar
            </button>

            <button
                type="submit"
                class="px-4 py-2 bg-[var(--tj-color-theme-primary)] hover:opacity-90 text-white rounded-lg font-medium transition"
            >
                Guardar
            </button>
        </div>
    </form>
</dialog>

<!-- MODAL ELIMINAR -->
<dialog id="modalDelete"
    class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-md
           fixed inset-0 m-auto shadow-xl border-0"
>
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Eliminar categoría</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar la categoría <strong id="delete-categoria-nombre"></strong>? 
            Esta acción no se puede deshacer.
        </p>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
            <button
                type="button"
                onclick="document.getElementById('modalDelete').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition"
            >
                Cancelar
            </button>

            <button
                type="button"
                onclick="confirmDelete()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition"
            >
                Eliminar
            </button>
        </div>
    </div>
</dialog>

<!-- Toast notifications -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 transition-all">
    <div class="flex items-center gap-3">
        <div id="toast-icon"></div>
        <div class="flex-1">
            <p id="toast-message" class="text-sm font-medium text-gray-900"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
// Estado global
let deleteId = null;
let todasCategorias = []; // Guardar todas las categorías para filtrado

// Cargar categorías al iniciar
document.addEventListener('DOMContentLoaded', function() {
    loadCategorias();
    
    // Filtro en tiempo real
    document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
});

// Cargar categorías desde el servidor
async function loadCategorias() {
    try {
        const response = await fetch('api/categorias/listar.php');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            todasCategorias = data.data;
            aplicarFiltros();
        } else {
            todasCategorias = [];
            mostrarEstadoVacio();
        }
    } catch (error) {
        showToast('Error al cargar las categorías', 'error');
        console.error('Error:', error);
    }
}

// Aplicar filtros
function aplicarFiltros() {
    const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
    
    const categoriasFiltradas = todasCategorias.filter(cat => {
        const coincideNombre = cat.nombre.toLowerCase().includes(filtroNombre);
        return coincideNombre;
    });
    
    renderCategorias(categoriasFiltradas);
}

// Renderizar categorías en la tabla
function renderCategorias(categorias) {
    const tbody = document.getElementById('categorias-tbody');
    const emptyState = document.getElementById('empty-state');
    
    if (categorias.length > 0) {
        tbody.innerHTML = categorias.map(cat => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${cat.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${escapeHtml(cat.nombre)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatDate(cat.created_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatDate(cat.updated_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                        onclick="openEditModal(${cat.id}, '${escapeHtml(cat.nombre)}')"
                        class="text-blue-600 hover:text-blue-900 mr-3"
                        title="Editar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        onclick="openDeleteModal(${cat.id}, '${escapeHtml(cat.nombre)}')"
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
    } else {
        mostrarEstadoVacio();
    }
}

// Mostrar estado vacío
function mostrarEstadoVacio() {
    const tbody = document.getElementById('categorias-tbody');
    const emptyState = document.getElementById('empty-state');
    tbody.innerHTML = '';
    emptyState.classList.remove('hidden');
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filtro-nombre').value = '';
    aplicarFiltros();
}

// Abrir modal para crear
function openCreateModal() {
    document.getElementById('categoria_id').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('modal-title').textContent = 'Nueva categoría';
    document.getElementById('modalForm').showModal();
}

// Abrir modal para editar
function openEditModal(id, nombre) {
    document.getElementById('categoria_id').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('modal-title').textContent = 'Editar categoría';
    document.getElementById('modalForm').showModal();
}

// Abrir modal para eliminar
function openDeleteModal(id, nombre) {
    deleteId = id;
    document.getElementById('delete-categoria-nombre').textContent = nombre;
    document.getElementById('modalDelete').showModal();
}

// Manejar submit del formulario
document.getElementById('formCategoria').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = document.getElementById('categoria_id').value;
    const nombre = document.getElementById('nombre').value.trim();
    
    if (!nombre) {
        showToast('El nombre es obligatorio', 'error');
        return;
    }
    
    const url = id ? 'api/categorias/editar.php' : 'api/categorias/crear.php';
    const data = id ? { id, nombre } : { nombre };
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message || 'Operación exitosa', 'success');
            document.getElementById('modalForm').close();
            loadCategorias();
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
        const response = await fetch('api/categorias/eliminar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: deleteId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message || 'Categoría eliminada', 'success');
            document.getElementById('modalDelete').close();
            loadCategorias();
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
</script>

<?php include __DIR__ . '/components/footer.php'; ?>
