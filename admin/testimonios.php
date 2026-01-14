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
                <h1 class="text-2xl font-bold text-gray-900">Testimonios</h1>
                <p class="text-gray-500 text-sm mt-1">Administra los testimonios de clientes</p>
            </div>
            <button
                onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo testimonio
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
                <div class="flex-1">
                    <input
                        type="text"
                        id="filtro-cargo"
                        placeholder="Buscar por cargo..."
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

        <!-- Tabla de testimonios -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Testimonio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha creación</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="testimonios-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay testimonios</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo testimonio.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm"
    class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto"
>
    <form id="formTestimonio" class="p-6 space-y-4">
        <input type="hidden" id="testimonio_id" name="id">
        
        <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Nuevo testimonio</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    required
                    maxlength="150"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: Juan Pérez"
                />
            </div>

            <div>
                <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo
                </label>
                <input
                    type="text"
                    id="cargo"
                    name="cargo"
                    maxlength="150"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ej: CEO en Empresa XYZ"
                />
            </div>
        </div>

        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                Foto del testimonio
            </label>
            <input
                type="file"
                id="foto"
                name="foto"
                accept="image/jpeg,image/png,image/jpg,image/webp"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            />
            <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, WEBP. Tamaño máximo: 5MB</p>
            <input type="hidden" id="foto_url" name="foto_url">
            <div id="preview-foto" class="mt-3 hidden">
                <p class="text-xs text-gray-600 mb-1">Vista previa:</p>
                <img id="preview-img" src="" alt="Vista previa" class="max-h-40 rounded-lg border shadow-sm">
            </div>
        </div>

        <div>
            <label for="testimonio" class="block text-sm font-medium text-gray-700 mb-2">
                Testimonio <span class="text-red-500">*</span>
            </label>
            <textarea
                id="testimonio"
                name="testimonio"
                required
                rows="5"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                placeholder="Escribe el testimonio del cliente..."
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres</p>
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
                <h2 class="text-xl font-semibold text-gray-900">Eliminar testimonio</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar el testimonio de <strong id="delete-testimonio-nombre"></strong>? 
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
let todosTestimonios = []; // Guardar todos los testimonios para filtrado

// Cargar testimonios al iniciar
document.addEventListener('DOMContentLoaded', function() {
    loadTestimonios();
    
    // Filtros en tiempo real
    document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
    document.getElementById('filtro-cargo').addEventListener('input', aplicarFiltros);
    
    // Vista previa de imagen
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            previewImage(file);
        }
    });
});

// Cargar testimonios desde el servidor
async function loadTestimonios() {
    try {
        const response = await fetch('api/testimonios/listar.php');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            todosTestimonios = data.data;
            aplicarFiltros();
        } else {
            todosTestimonios = [];
            mostrarEstadoVacio();
        }
    } catch (error) {
        showToast('Error al cargar los testimonios', 'error');
        console.error('Error:', error);
    }
}

// Aplicar filtros
function aplicarFiltros() {
    const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
    const filtroCargo = document.getElementById('filtro-cargo').value.toLowerCase();
    
    const testimoniosFiltrados = todosTestimonios.filter(test => {
        const coincideNombre = test.nombre.toLowerCase().includes(filtroNombre);
        const coincideCargo = !test.cargo || test.cargo.toLowerCase().includes(filtroCargo);
        return coincideNombre && coincideCargo;
    });
    
    renderTestimonios(testimoniosFiltrados);
}

// Renderizar testimonios en la tabla
function renderTestimonios(testimonios) {
    const tbody = document.getElementById('testimonios-tbody');
    const emptyState = document.getElementById('empty-state');
    
    if (testimonios.length > 0) {
        tbody.innerHTML = testimonios.map(test => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${test.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${test.foto_url ? 
                        `<img src="../${escapeHtml(test.foto_url)}" alt="${escapeHtml(test.nombre)}" class="w-10 h-10 rounded-full object-cover" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23ccc%27%3E%3Cpath d=%27M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z%27/%3E%3C/svg%3E';">` :
                        `<div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>`
                    }
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${escapeHtml(test.nombre)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-500">${test.cargo ? escapeHtml(test.cargo) : '-'}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-500 line-clamp-2">${escapeHtml(test.testimonio)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatDate(test.fecha_creacion)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                        onclick='openEditModal(${JSON.stringify(test).replace(/'/g, "&apos;")})'
                        class="text-blue-600 hover:text-blue-900 mr-3"
                        title="Editar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        onclick="openDeleteModal(${test.id}, '${escapeHtml(test.nombre)}')"
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
    const tbody = document.getElementById('testimonios-tbody');
    const emptyState = document.getElementById('empty-state');
    tbody.innerHTML = '';
    emptyState.classList.remove('hidden');
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filtro-nombre').value = '';
    document.getElementById('filtro-cargo').value = '';
    aplicarFiltros();
}

// Abrir modal para crear
function openCreateModal() {
    document.getElementById('testimonio_id').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('cargo').value = '';
    document.getElementById('foto').value = '';
    document.getElementById('foto_url').value = '';
    document.getElementById('testimonio').value = '';
    document.getElementById('preview-foto').classList.add('hidden');
    document.getElementById('modal-title').textContent = 'Nuevo testimonio';
    document.getElementById('modalForm').showModal();
}

// Abrir modal para editar
function openEditModal(testimonio) {
    document.getElementById('testimonio_id').value = testimonio.id;
    document.getElementById('nombre').value = testimonio.nombre;
    document.getElementById('cargo').value = testimonio.cargo || '';
    document.getElementById('foto').value = '';
    document.getElementById('foto_url').value = testimonio.foto_url || '';
    document.getElementById('testimonio').value = testimonio.testimonio;
    
    // Mostrar vista previa si hay foto
    if (testimonio.foto_url) {
        document.getElementById('preview-img').src = '../' + testimonio.foto_url;
        document.getElementById('preview-foto').classList.remove('hidden');
    } else {
        document.getElementById('preview-foto').classList.add('hidden');
    }
    
    document.getElementById('modal-title').textContent = 'Editar testimonio';
    document.getElementById('modalForm').showModal();
}

// Abrir modal para eliminar
function openDeleteModal(id, nombre) {
    deleteId = id;
    document.getElementById('delete-testimonio-nombre').textContent = nombre;
    document.getElementById('modalDelete').showModal();
}

// Manejar submit del formulario
document.getElementById('formTestimonio').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = document.getElementById('testimonio_id').value;
    const nombre = document.getElementById('nombre').value.trim();
    const cargo = document.getElementById('cargo').value.trim();
    const fotoFile = document.getElementById('foto').files[0];
    let foto_url = document.getElementById('foto_url').value.trim();
    const testimonio = document.getElementById('testimonio').value.trim();
    
    if (!nombre) {
        showToast('El nombre es obligatorio', 'error');
        return;
    }
    
    if (!testimonio || testimonio.length < 10) {
        showToast('El testimonio debe tener al menos 10 caracteres', 'error');
        return;
    }
    
    try {
        // Si hay un archivo nuevo, subirlo primero
        if (fotoFile) {
            const formData = new FormData();
            formData.append('imagen', fotoFile);
            
            const uploadResponse = await fetch('api/testimonios/subir-imagen.php', {
                method: 'POST',
                body: formData
            });
            
            const uploadResult = await uploadResponse.json();
            
            if (uploadResult.success) {
                foto_url = uploadResult.data.url;
            } else {
                console.error('Error al subir imagen:', uploadResult);
                let errorMsg = uploadResult.message || 'Error al subir la imagen';
                
                // Mostrar información de debug si está disponible
                if (uploadResult.debug) {
                    console.error('Debug info:', uploadResult.debug);
                    errorMsg += '\n\nRevisa la consola del navegador para más detalles.';
                }
                
                showToast(errorMsg, 'error');
                return;
            }
        }
        
        // Ahora guardar/actualizar el testimonio
        const url = id ? 'api/testimonios/editar.php' : 'api/testimonios/crear.php';
        const data = {
            nombre,
            cargo: cargo || null,
            foto_url: foto_url || null,
            testimonio
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
            loadTestimonios();
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
        const response = await fetch('api/testimonios/eliminar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: deleteId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message || 'Testimonio eliminado', 'success');
            document.getElementById('modalDelete').close();
            loadTestimonios();
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
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        
        reader.readAsDataURL(file);
    }
}
</script>

<?php include __DIR__ . '/components/footer.php'; ?>

