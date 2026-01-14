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
                <h1 class="text-2xl font-bold text-gray-900">Socios</h1>
                <p class="text-gray-500 text-sm mt-1">Administra los socios corporativos, profesionales y aliados</p>
            </div>
            <button
                onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo socio
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <select
                    id="filtro-tipo"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
                    <option value="todos">Todos los tipos</option>
                    <option value="institucional">Institucionales</option>
                    <option value="natural">Personas Naturales</option>
                    <option value="empresa">Empresas</option>
                </select>
                <input
                    type="text"
                    id="filtro-nombre"
                    placeholder="Buscar por nombre..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                />
                <button
                    onclick="limpiarFiltros()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition flex items-center gap-2 justify-center"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen/Icono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo/Info</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay socios</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo socio.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm"
    class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-4xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto"
>
    <form id="formSocio" class="p-6 space-y-5">
        <input type="hidden" id="socio_id" name="id">
        
        <div class="flex justify-between items-center border-b pb-3">
            <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Nuevo socio</h2>
            <button type="button" onclick="document.getElementById('modalForm').close()" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tipo -->
            <div class="col-span-2">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Socio <span class="text-red-500">*</span></label>
                <select id="tipo" name="tipo" onchange="toggleFields()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                    <option value="institucional">Institucional</option>
                    <option value="natural">Persona Natural</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>

            <!-- Nombre -->
            <div class="col-span-2">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre <span class="text-red-500">*</span></label>
                <input type="text" id="nombre" name="nombre" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
            </div>

            <!-- Campos específicos Profesional -->
            <div class="field-profesional hidden col-span-2 md:col-span-1">
                <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                <input type="text" id="cargo" name="cargo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="field-profesional hidden col-span-2 md:col-span-1">
                <label for="educacion" class="block text-sm font-medium text-gray-700 mb-2">Educación</label>
                <input type="text" id="educacion" name="educacion" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="field-profesional hidden col-span-2">
                <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <!-- Imagen / Icono -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen / Logo <span class="text-red-500">*</span></label>
                
                <!-- Upload para Todos -->
                <div id="upload-container">
                    <div class="flex items-center justify-center w-full">
                        <label for="imagen-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click para subir</span></p>
                            </div>
                            <input id="imagen-upload" type="file" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </label>
                    </div>
                    <div id="preview-container" class="mt-3 hidden">
                        <p class="text-xs text-gray-600 mb-1">Vista previa:</p>
                        <img id="preview-img" src="" class="h-20 w-20 object-cover rounded-lg border shadow-sm">
                    </div>
                </div>
                
                <input type="hidden" id="imagen-final" name="imagen_final">
            </div>

            <!-- Descripción Corta -->
            <div class="col-span-2">
                <label for="descripcion_corta" class="block text-sm font-medium text-gray-700 mb-2">Descripción Corta</label>
                <textarea id="descripcion_corta" name="descripcion_corta" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"></textarea>
            </div>

            <!-- Descripción Completa -->
            <div class="col-span-2">
                <label for="descripcion_completa" class="block text-sm font-medium text-gray-700 mb-2">Descripción Completa</label>
                <textarea id="descripcion_completa" name="descripcion_completa" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"></textarea>
            </div>

            <!-- Servicios / Especialidades -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Servicios / Especialidades</label>
                <div id="servicios-container" class="space-y-2">
                    <!-- Inputs dinámicos -->
                </div>
                <button type="button" onclick="addServicio()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">+ Agregar item</button>
            </div>

            <!-- Beneficios (Solo Aliados) -->
            <div class="field-aliado hidden col-span-2">
                <label for="beneficios" class="block text-sm font-medium text-gray-700 mb-2">Beneficios</label>
                <textarea id="beneficios" name="beneficios" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"></textarea>
            </div>

            <!-- Contacto -->
            <div class="col-span-2 md:col-span-1">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="col-span-2 md:col-span-1">
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="col-span-2">
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Sitio Web</label>
                <input type="url" id="website" name="website" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <!-- Orden -->
            <div class="col-span-2 md:col-span-1">
                <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                <input type="number" id="orden" name="orden" value="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
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
                <h2 class="text-xl font-semibold text-gray-900">Eliminar socio</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar al socio <strong id="delete-socio-nombre"></strong>? 
            Esta acción no se puede deshacer.
        </p>

        <input type="hidden" id="delete-socio-id">

        <div class="flex justify-end gap-2 pt-4">
            <button
                type="button"
                onclick="document.getElementById('modalDelete').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition"
            >
                Cancelar
            </button>
            <button
                onclick="confirmarEliminar()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition"
            >
                Eliminar
            </button>
        </div>
    </div>
</dialog>

<!-- Toast de notificaciones -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity">
    <span id="toast-message"></span>
</div>

<script>
let sociosData = [];

document.addEventListener('DOMContentLoaded', () => {
    loadSocios();
    
    // Event listeners para filtros
    document.getElementById('filtro-tipo').addEventListener('change', aplicarFiltros);
    document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
    
    // Event listener para el formulario
    document.getElementById('formSocio').addEventListener('submit', handleSubmit);
});

async function loadSocios() {
    try {
        const response = await fetch('api/socios/listar.php');
        const data = await response.json();
        
        if (data.success) {
            sociosData = data.data;
            renderSocios(sociosData);
        } else {
            showToast('Error al cargar socios: ' + data.message, 'error');
        }
    } catch (error) {
        showToast('Error de conexión: ' + error.message, 'error');
    }
}

function renderSocios(socios) {
    const tbody = document.getElementById('socios-tbody');
    const emptyState = document.getElementById('empty-state');
    
    if (socios.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    
    tbody.innerHTML = socios.map(socio => {
        let imagenHtml = socio.imagen ? 
            `<img src="../${socio.imagen}" class="w-10 h-10 rounded-lg object-cover bg-gray-50">` : 
            '<div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400"><i class="fa-solid fa-image"></i></div>';
            
        // Map types to readable labels
        const typeLabels = {
            'institucional': 'Institucional',
            'natural': 'Persona Natural',
            'empresa': 'Empresa'
        };

        return `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${socio.orden}</td>
            <td class="px-6 py-4 whitespace-nowrap">${imagenHtml}</td>
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${escapeHtml(socio.nombre)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                    ${typeLabels[socio.tipo] || socio.tipo}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(socio.cargo || socio.descripcion_corta || '-')}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                    onclick='editarSocio(${JSON.stringify(socio).replace(/'/g, "&apos;")})'
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
}

function aplicarFiltros() {
    const filtroTipo = document.getElementById('filtro-tipo').value;
    const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
    
    const sociosFiltrados = sociosData.filter(socio => {
        const matchTipo = filtroTipo === 'todos' || socio.tipo === filtroTipo;
        const matchNombre = filtroNombre === '' || socio.nombre.toLowerCase().includes(filtroNombre);
        
        return matchTipo && matchNombre;
    });
    
    renderUsuarios(sociosFiltrados); // Reusing render logic
}

function limpiarFiltros() {
    document.getElementById('filtro-tipo').value = 'todos';
    document.getElementById('filtro-nombre').value = '';
    renderSocios(sociosData);
}

function toggleFields() {
    const tipo = document.getElementById('tipo').value;
    
    // Mostrar/Ocultar campos Profesional
    document.querySelectorAll('.field-profesional').forEach(el => {
        el.classList.toggle('hidden', tipo !== 'profesional');
    });

    // Mostrar/Ocultar campos Aliado
    document.querySelectorAll('.field-aliado').forEach(el => {
        el.classList.toggle('hidden', tipo !== 'aliado');
    });

    // Toggle Imagen Upload vs Icon Input - YA NO ES NECESARIO, SIEMPRE ES UPLOAD
    // const uploadContainer = document.getElementById('upload-container');
    // const iconInputContainer = document.getElementById('icon-input-container');
    
    // if (tipo === 'profesional') {
    //     uploadContainer.classList.remove('hidden');
    //     iconInputContainer.classList.add('hidden');
    // } else {
    //     uploadContainer.classList.add('hidden');
    //     iconInputContainer.classList.remove('hidden');
    // }
}

function addServicio(valor = '') {
    const container = document.getElementById('servicios-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="servicios[]" value="${valor}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Servicio / Especialidad">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

function openCreateModal() {
    document.getElementById('modal-title').textContent = 'Nuevo Socio';
    document.getElementById('formSocio').reset();
    document.getElementById('socio_id').value = '';
    document.getElementById('servicios-container').innerHTML = '';
    addServicio(); // Agregar uno vacío por defecto
    document.getElementById('preview-container').classList.add('hidden');
    document.getElementById('imagen-final').value = '';
    
    toggleFields();
    document.getElementById('modalForm').showModal();
}

function editarSocio(socio) {
    document.getElementById('modal-title').textContent = 'Editar Socio';
    document.getElementById('socio_id').value = socio.id;
    document.getElementById('tipo').value = socio.tipo;
    document.getElementById('nombre').value = socio.nombre;
    document.getElementById('cargo').value = socio.cargo || '';
    document.getElementById('educacion').value = socio.educacion || '';
    document.getElementById('linkedin').value = socio.linkedin || '';
    document.getElementById('descripcion_corta').value = socio.descripcion_corta || '';
    document.getElementById('descripcion_completa').value = socio.descripcion_completa || '';
    document.getElementById('beneficios').value = socio.beneficios || '';
    document.getElementById('email').value = socio.email || '';
    document.getElementById('telefono').value = socio.telefono || '';
    document.getElementById('website').value = socio.website || '';
    document.getElementById('orden').value = socio.orden || 0;

    // Imagen
    document.getElementById('imagen-final').value = socio.imagen || '';
    if (socio.imagen) {
        document.getElementById('preview-img').src = '../' + socio.imagen;
        document.getElementById('preview-container').classList.remove('hidden');
    } else {
        document.getElementById('preview-container').classList.add('hidden');
    }

    // Servicios
    const container = document.getElementById('servicios-container');
    container.innerHTML = '';
    if (socio.servicios && socio.servicios.length > 0) {
        socio.servicios.forEach(s => addServicio(s));
    } else {
        addServicio();
    }

    toggleFields();
    document.getElementById('modalForm').showModal();
}

async function previewImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('imagen', input.files[0]);

        try {
            const response = await fetch('api/socios/subir-imagen.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                document.getElementById('imagen-final').value = data.data.url;
                document.getElementById('preview-img').src = '../' + data.data.url;
                document.getElementById('preview-container').classList.remove('hidden');
                showToast('Imagen subida exitosamente', 'success');
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error al subir la imagen', 'error');
        }
    }
}

async function handleSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Procesar servicios
    const serviciosInputs = document.getElementsByName('servicios[]');
    data.servicios = Array.from(serviciosInputs).map(input => input.value).filter(val => val.trim() !== '');

    // Procesar imagen (siempre usa el upload)
    data.imagen = document.getElementById('imagen-final').value;

    const id = document.getElementById('socio_id').value;
    const url = id ? 'api/socios/editar.php' : 'api/socios/crear.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
            document.getElementById('modalForm').close();
            loadSocios();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error al guardar', 'error');
    }
}

function openDeleteModal(id, nombre) {
    document.getElementById('delete-socio-id').value = id;
    document.getElementById('delete-socio-nombre').textContent = nombre;
    document.getElementById('modalDelete').showModal();
}

async function confirmarEliminar() {
    const id = document.getElementById('delete-socio-id').value;
    
    try {
        const response = await fetch('api/socios/eliminar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        });
        
        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            document.getElementById('modalDelete').close();
            loadSocios();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error al eliminar', 'error');
    }
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    toast.classList.remove('hidden', 'bg-gray-900', 'bg-green-600', 'bg-red-600');
    
    if (type === 'success') {
        toast.classList.add('bg-green-600');
    } else if (type === 'error') {
        toast.classList.add('bg-red-600');
    } else {
        toast.classList.add('bg-gray-900');
    }
    
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, m => map[m]);
}
</script>

<?php include 'components/footer.php'; ?>
