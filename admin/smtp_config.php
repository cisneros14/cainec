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
                <h1 class="text-2xl font-bold text-gray-900">Configuración SMTP</h1>
                <p class="text-gray-500 text-sm mt-1">Administra las credenciales SMTP para el envío de correos por usuario</p>
            </div>
            <button
    id="btn-nueva-config"
    onclick="openCreateModal()"
    class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
>
    Nueva Configuración
</button>
        </div>

        <!-- Tabla de configuraciones -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Host / Puerto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Remitente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="configs-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay configuraciones</h3>
                <p class="mt-1 text-sm text-gray-500">Crea una nueva configuración SMTP para comenzar.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm"
    class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto"
>
    <form id="formConfig" class="p-6 space-y-5">
        <input type="hidden" id="config_id" name="id">
        
        <div class="flex justify-between items-center border-b pb-3">
            <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Nueva Configuración SMTP</h2>
            <button type="button" onclick="document.getElementById('modalForm').close()" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Usuario -->
            <div class="col-span-2">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuario <span class="text-red-500">*</span></label>
                
                <!-- Buscador de usuarios -->
               

                <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <p class="text-xs text-gray-500 mt-1">Selecciona un usuario de la lista filtrada.</p>
            </div>

            <!-- SMTP Host -->
            <div class="col-span-2 md:col-span-1">
                <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host <span class="text-red-500">*</span></label>
                <input type="text" id="smtp_host" name="smtp_host" placeholder="smtp.example.com" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
            </div>

            <!-- SMTP Port -->
            <div class="col-span-2 md:col-span-1">
                <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-2">Puerto <span class="text-red-500">*</span></label>
                <input type="number" id="smtp_port" name="smtp_port" value="587" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
            </div>

            <!-- Username -->
            <div class="col-span-2 md:col-span-1">
                <label for="smtp_username" class="block text-sm font-medium text-gray-700 mb-2">Usuario SMTP <span class="text-red-500">*</span></label>
                <input type="text" id="smtp_username" name="smtp_username" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
            </div>

            <!-- Password -->
            <div class="col-span-2 md:col-span-1">
                <label for="smtp_password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña SMTP <span class="text-red-500">*</span></label>
                <input type="password" id="smtp_password" name="smtp_password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
            </div>

            <!-- Encryption -->
            <div class="col-span-2 md:col-span-1">
                <label for="encryption" class="block text-sm font-medium text-gray-700 mb-2">Encriptación</label>
                <select id="encryption" name="encryption" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="tls">TLS</option>
                    <option value="ssl">SSL</option>
                    <option value="none">Ninguna</option>
                </select>
            </div>

            <!-- From Email -->
            <div class="col-span-2 md:col-span-1">
                <label for="from_email" class="block text-sm font-medium text-gray-700 mb-2">Email Remitente <span class="text-red-500">*</span></label>
                <input type="email" id="from_email" name="from_email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
            </div>

            <!-- From Name -->
            <div class="col-span-2">
                <label for="from_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Remitente</label>
                <input type="text" id="from_name" name="from_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <!-- Checkboxes -->
            <div class="col-span-2 flex gap-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_active" name="is_active" class="sr-only peer" checked>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-700">Activo</span>
                </label>

                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_default" name="is_default" class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-700">Predeterminado</span>
                </label>
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
                <h2 class="text-xl font-semibold text-gray-900">Eliminar configuración</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar esta configuración SMTP? 
            Esta acción no se puede deshacer.
        </p>

        <input type="hidden" id="delete-config-id">

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

<dialog id="modalTest" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-md fixed inset-0 m-auto shadow-xl border-0">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Probar conexión SMTP</h2>
        </div>

        <p class="text-sm text-gray-500">Se enviará un correo electrónico de prueba utilizando esta configuración.</p>
        
        <input type="hidden" id="test-config-id">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo de destino</label>
            <input type="email" id="test-email-dest" placeholder="ejemplo@correo.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="document.getElementById('modalTest').close()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium">
                Cancelar
            </button>
            <button id="btn-confirm-test" onclick="enviarPrueba()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium flex items-center gap-2">
                <span>Enviar prueba</span>
            </button>
        </div>
    </div>
</dialog>
<!-- Toast de notificaciones -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity">
    <span id="toast-message"></span>
</div>

<script>
let configsData = [];
let usuariosData = [];

document.addEventListener('DOMContentLoaded', () => {
    loadConfigs();
    loadUsuarios();
    
    // Event listener para el formulario
    document.getElementById('formConfig').addEventListener('submit', handleSubmit);
});

async function loadConfigs() {
    try {
        const response = await fetch('api/smtp_config/listar.php');
        const data = await response.json();
        
        if (data.success) {
            configsData = data.data;
            renderConfigs(configsData);
        } else {
            showToast('Error al cargar configuraciones: ' + data.message, 'error');
        }
    } catch (error) {
        showToast('Error de conexión: ' + error.message, 'error');
    }
}

async function loadUsuarios() {
    try {
        const response = await fetch('api/usuarios/listar.php');
        const data = await response.json();
        
        if (data.success) {
            usuariosData = data.data;
        
        }
    } catch (error) {
        console.error('Error cargando usuarios:', error);
    }
}



function filtrarUsuarios(termino) {
    const search = termino.toLowerCase().trim();
    
    

    const usuariosFiltrados = usuariosData.filter(user => {
        // Buscar en todas las propiedades del objeto usuario
        return Object.values(user).some(val => 
            val !== null && 
            val !== undefined && 
            String(val).toLowerCase().includes(search)
        );
    });


}

function renderConfigs(configs) {
    const tbody = document.getElementById('configs-tbody');
    const emptyState = document.getElementById('empty-state');
    const btnNuevo = document.getElementById('btn-nueva-config'); // Referencia al botón
    
    // Si ya existe al menos 1 configuración, ocultamos el botón de "Nuevo"
    if (configs.length >= 1) {
        btnNuevo.classList.add('hidden');
    } else {
        btnNuevo.classList.remove('hidden');
    }

    if (configs.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    
    tbody.innerHTML = configs.map(config => {
        const statusBadge = config.is_active == 1 
            ? '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>'
            : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>';
            
        const defaultBadge = config.is_default == 1
            ? '<span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Default</span>'
            : '';

        const userName = config.usuario_nombre ? `${config.usuario_nombre} ${config.usuario_apellido}` : 'Usuario desconocido';

        return `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${escapeHtml(userName)}</div>
                <div class="text-sm text-gray-500">${escapeHtml(config.usuario_email || '')}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${escapeHtml(config.smtp_host)}</div>
                <div class="text-sm text-gray-500">Puerto: ${config.smtp_port} (${config.encryption})</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${escapeHtml(config.from_email)}</div>
                <div class="text-sm text-gray-500">${escapeHtml(config.from_name || '')}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${statusBadge}
                ${defaultBadge}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                    onclick='editarConfig(${JSON.stringify(config).replace(/'/g, "&apos;")})'
                    class="text-blue-600 hover:text-blue-900 mr-3"
                    title="Editar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                <button
                    onclick="openDeleteModal(${config.id})"
                    class="text-red-600 hover:text-red-900"
                    title="Eliminar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
           <td> <button
    onclick='openTestModal(${config.id})'
    class="text-green-600 hover:text-green-900 mr-3"
    title="Probar Configuración"
>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
</button></td>
        </tr>
        `;
    }).join('');
} 

function openCreateModal() {
    document.getElementById('modal-title').textContent = 'Nueva Configuración SMTP';
    document.getElementById('formConfig').reset();
    document.getElementById('config_id').value = '';
    document.getElementById('is_active').checked = true;
    
    // Resetear búsqueda y lista de usuarios
 
  
    
    document.getElementById('modalForm').showModal();
}

function editarConfig(config) {
    document.getElementById('modal-title').textContent = 'Editar Configuración SMTP';
    document.getElementById('config_id').value = config.id;
    document.getElementById('user_id').value = config.user_id;
    document.getElementById('smtp_host').value = config.smtp_host;
    document.getElementById('smtp_port').value = config.smtp_port;
    document.getElementById('smtp_username').value = config.smtp_username;
    document.getElementById('smtp_password').value = config.smtp_password;
    document.getElementById('encryption').value = config.encryption;
    document.getElementById('from_email').value = config.from_email;
    document.getElementById('from_name').value = config.from_name || '';
    document.getElementById('is_active').checked = config.is_active == 1;
    document.getElementById('is_default').checked = config.is_default == 1;
    
    // Resetear búsqueda y asegurar que el usuario seleccionado esté visible
   
   
    document.getElementById('user_id').value = config.user_id;

    document.getElementById('modalForm').showModal();
}

async function handleSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Checkboxes handling
    data.is_active = document.getElementById('is_active').checked;
    data.is_default = document.getElementById('is_default').checked;

    const id = document.getElementById('config_id').value;
    const url = id ? 'api/smtp_config/editar.php' : 'api/smtp_config/crear.php';

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
            loadConfigs();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error al guardar', 'error');
    }
}

function openDeleteModal(id) {
    document.getElementById('delete-config-id').value = id;
    document.getElementById('modalDelete').showModal();
}

async function confirmarEliminar() {
    const id = document.getElementById('delete-config-id').value;
    
    try {
        const response = await fetch('api/smtp_config/eliminar.php', {
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
            loadConfigs();
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


function openTestModal(id) {
    document.getElementById('test-config-id').value = id;
    document.getElementById('test-email-dest').value = ''; // Limpiar campo
    document.getElementById('modalTest').showModal();
}

async function enviarPrueba() {
    const id = document.getElementById('test-config-id').value;
    const email = document.getElementById('test-email-dest').value;
    const btn = document.getElementById('btn-confirm-test');
    const originalBtnText = btn.innerHTML; // Guardamos el texto original

    if (!email) {
        showToast('Por favor, ingresa un correo de destino', 'error');
        return;
    }

    // 1. Bloquear botón y mostrar estado de carga
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Enviando...
    `;
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    try {
        const response = await fetch('api/smtp_config/probar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, email_destino: email })
        });

        // Intentamos leer la respuesta, incluso si no es un JSON perfecto
        const text = await response.text();
        let data;
        
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("Respuesta no válida del servidor:", text);
            throw new Error("El servidor respondió con un formato incorrecto. Revisa la consola.");
        }

        if (data.success) {
            // 2. ÉXITO: Mostrar Toast Verde y Cerrar Modal
            showToast(data.message, 'success'); 
            document.getElementById('modalTest').close();
        } else {
            // 3. ERROR DEL PHP: Mostrar Toast Rojo
            showToast(data.message, 'error');
        }

    } catch (error) {
        console.error(error);
        showToast('Error de comunicación: ' + error.message, 'error');
    } finally {
        // 4. Restaurar botón siempre (haya éxito o error)
        btn.disabled = false;
        btn.innerHTML = originalBtnText; // Restaurar texto original
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
    }
}
</script>

<?php include 'components/footer.php'; ?>
