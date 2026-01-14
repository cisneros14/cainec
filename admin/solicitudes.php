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
                <h1 class="text-2xl font-bold text-gray-900">Solicitudes de Registro</h1>
                <p class="text-gray-500 text-sm mt-1">Aprueba o rechaza las solicitudes de nuevos usuarios</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" id="filtro-nombre" placeholder="Buscar por nombre..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                <select id="filtro-estado"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="rechazado">Rechazado</option>
                </select>
                <button onclick="limpiarFiltros()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </button>
            </div>

            <div class="mt-4 flex items-center">
                <button id="btn-atendidas" onclick="toggleAtendidas()"
                    class="px-4 py-2 rounded-lg font-medium transition flex items-center gap-2 border border-gray-300 bg-white text-gray-700">
                    <svg id="icon-atendidas" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Solicitudes atendidas</span>
                </button>
            </div>
        </div>

        <!-- Tabla de solicitudes -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Registro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="solicitudes-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron registros con los filtros actuales.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CAMBIAR ESTADO -->
<dialog id="modalEstado" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl
           fixed inset-0 m-auto shadow-xl border-0">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-3 border-b pb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Gestionar Solicitud</h2>
                <p class="text-sm text-gray-500">Usuario: <strong id="estado-usuario-nombre"></strong></p>
            </div>
        </div>

        <input type="hidden" id="estado-usuario-id">

        <!-- Contenedor de detalles del usuario -->
        <div id="usuario-detalles" class="max-h-[50vh] overflow-y-auto pr-2">
            <!-- Se llena dinámicamente -->
        </div>

        <div id="modal-actions" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t">
            <!-- Botones generados dinámicamente -->
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" onclick="document.getElementById('modalEstado').close()"
                class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium transition text-sm">
                Cerrar sin cambios
            </button>
        </div>
    </div>
</dialog>

<!-- Toast de notificaciones -->
<div id="toast"
    class="hidden fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity">
    <span id="toast-message"></span>
</div>

<script>
    let solicitudesData = [];
    let mostrarAtendidas = false;

    // Cargar solicitudes al iniciar
    document.addEventListener('DOMContentLoaded', () => {
        loadSolicitudes();

        // Event listeners para filtros
        document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
        document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
    });

    function toggleAtendidas() {
        mostrarAtendidas = !mostrarAtendidas;
        const btn = document.getElementById('btn-atendidas');
        const icon = document.getElementById('icon-atendidas');

        if (mostrarAtendidas) {
            btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
            btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-white');
        } else {
            btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
            icon.classList.add('text-gray-400');
            icon.classList.remove('text-white');
        }
        aplicarFiltros();
    }

    async function loadSolicitudes() {
        try {
            const response = await fetch('api/usuarios/listar.php');
            const data = await response.json();

            if (data.success) {
                solicitudesData = data.data;
                aplicarFiltros();
            } else {
                showToast('Error al cargar solicitudes: ' + data.message, 'error');
            }
        } catch (error) {
            showToast('Error de conexión: ' + error.message, 'error');
        }
    }

    function renderSolicitudes(solicitudes) {
        const tbody = document.getElementById('solicitudes-tbody');
        const emptyState = document.getElementById('empty-state');

        if (solicitudes.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        tbody.innerHTML = solicitudes.map(usuario => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${usuario.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${escapeHtml(usuario.nombre)} ${escapeHtml(usuario.apellido)}</div>
                <div class="text-sm text-gray-500">${escapeHtml(usuario.empresa || '-')}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                    ${capitalize(usuario.tipo_socio || 'N/A')}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(usuario.email)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${formatDate(usuario.fecha_creacion)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${getStatusBadge(usuario.estado)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                    onclick="openEstadoModal(${usuario.id})"
                    class="text-[var(--tj-color-theme-primary)] hover:text-blue-800 font-medium"
                >
                    Gestionar
                </button>
            </td>
        </tr>
    `).join('');
    }

    function aplicarFiltros() {
        const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
        const filtroEstado = document.getElementById('filtro-estado').value;

        const filtrados = solicitudesData.filter(usuario => {
            const nombreCompleto = `${usuario.nombre} ${usuario.apellido}`.toLowerCase();
            const matchNombre = filtroNombre === '' || nombreCompleto.includes(filtroNombre);

            let estadoStr = '';
            if (usuario.estado == 1) estadoStr = 'activo';
            else if (usuario.estado == 0) estadoStr = 'inactivo';
            else estadoStr = usuario.estado;

            // Lógica del switch "Solicitudes atendidas"
            let matchAtendidas = true;
            if (mostrarAtendidas) {
                // Si está activado, mostrar TODO MENOS pendientes
                if (estadoStr === 'pendiente') matchAtendidas = false;
            } else {
                // Si está desactivado (default), mostrar SOLO pendientes
                if (estadoStr !== 'pendiente') matchAtendidas = false;
            }

            // El filtro de estado (dropdown) sigue funcionando sobre el conjunto resultante
            const matchEstado = filtroEstado === '' || estadoStr === filtroEstado;

            return matchNombre && matchEstado && matchAtendidas;
        });

        renderSolicitudes(filtrados);
    }

    function limpiarFiltros() {
        document.getElementById('filtro-nombre').value = '';
        document.getElementById('filtro-estado').value = '';
        if (mostrarAtendidas) toggleAtendidas();
        aplicarFiltros();
    }

    function openEstadoModal(id) {
        const usuario = solicitudesData.find(u => u.id == id);
        if (!usuario) return;

        document.getElementById('estado-usuario-id').value = usuario.id;
        document.getElementById('estado-usuario-nombre').textContent = `${usuario.nombre} ${usuario.apellido}`;

        // Generar detalles dinámicos
        const detailsContainer = document.getElementById('usuario-detalles');
        let html = '';

        // Campos comunes
        html += `
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h3 class="text-sm font-bold text-gray-700 mb-2 border-b pb-1">Información General</h3>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                    <div><span class="text-gray-500">Tipo:</span> <span class="font-medium">${capitalize(usuario.tipo_socio)}</span></div>
                    <div><span class="text-gray-500">Cédula/RUC:</span> <span class="font-medium">${escapeHtml(usuario.cedula_ruc)}</span></div>
                    <div><span class="text-gray-500">Email:</span> <span class="font-medium">${escapeHtml(usuario.email)}</span></div>
                    <div><span class="text-gray-500">Teléfono:</span> <span class="font-medium">${escapeHtml(usuario.telefono_contacto)}</span></div>
                    <div><span class="text-gray-500">Ubicación:</span> <span class="font-medium">${escapeHtml(usuario.ciudad)}, ${escapeHtml(usuario.provincia)}</span></div>
                </div>
            </div>
        `;

        // Campos específicos
        if (usuario.tipo_socio === 'natural') {
            html += `
                <div class="bg-blue-50 p-4 rounded-lg mb-4">
                    <h3 class="text-sm font-bold text-blue-800 mb-2 border-b border-blue-200 pb-1">Perfil Profesional (Natural)</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div><span class="text-gray-500">Fecha Nacimiento:</span> <span class="font-medium">${formatDate(usuario.fecha_nacimiento)}</span></div>
                        <div><span class="text-gray-500">Género:</span> <span class="font-medium">${escapeHtml(usuario.genero)}</span></div>
                        <div><span class="text-gray-500">Nivel Educación:</span> <span class="font-medium">${escapeHtml(usuario.nivel_educacion)}</span></div>
                        <div><span class="text-gray-500">Registro Prof.:</span> <span class="font-medium">${escapeHtml(usuario.registro_profesional)}</span></div>
                        <div><span class="text-gray-500">Actividad:</span> <span class="font-medium">${escapeHtml(usuario.actividad)}</span></div>
                        <div><span class="text-gray-500">Ciudad Ops:</span> <span class="font-medium">${escapeHtml(usuario.ciudad_operaciones)}</span></div>
                        <div><span class="text-gray-500">Plazas Trabajo:</span> <span class="font-medium">${escapeHtml(usuario.plazas_trabajo_generadas)}</span></div>
                    </div>
                     <div class="mt-2 text-sm">
                        <span class="text-gray-500 block">Formación:</span>
                        <p class="text-gray-800 bg-white p-2 rounded border border-blue-100">${escapeHtml(usuario.formacion || 'N/A')}</p>
                    </div>
                </div>
            `;
        } else if (usuario.tipo_socio === 'juridica') {
            html += `
                <div class="bg-purple-50 p-4 rounded-lg mb-4">
                    <h3 class="text-sm font-bold text-purple-800 mb-2 border-b border-purple-200 pb-1">Perfil Empresarial (Jurídica)</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="col-span-2"><span class="text-gray-500">Razón Social:</span> <span class="font-medium">${escapeHtml(usuario.nombre_juridico)}</span></div>
                        <div class="col-span-2"><span class="text-gray-500">Nombre Comercial:</span> <span class="font-medium">${escapeHtml(usuario.empresa)}</span></div>
                        <div><span class="text-gray-500">Rep. Legal:</span> <span class="font-medium">${escapeHtml(usuario.representante_legal)}</span></div>
                        <div><span class="text-gray-500">Cargo quien registra:</span> <span class="font-medium">${escapeHtml(usuario.cargo)}</span></div>
                        <div><span class="text-gray-500">Inicio Actividades:</span> <span class="font-medium">${formatDate(usuario.inicio_actividades)}</span></div>
                        <div><span class="text-gray-500">Plazas Trabajo:</span> <span class="font-medium">${escapeHtml(usuario.plazas_trabajo_generadas)}</span></div>
                        <div><span class="text-gray-500">Directiva:</span> <span class="font-medium">${usuario.directiva == 1 ? 'Sí' : 'No'}</span></div>
                    </div>
                </div>
            `;
        } else if (usuario.tipo_socio === 'organizacion') {
            html += `
                <div class="bg-orange-50 p-4 rounded-lg mb-4">
                    <h3 class="text-sm font-bold text-orange-800 mb-2 border-b border-orange-200 pb-1">Perfil Organización</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="col-span-2"><span class="text-gray-500">Nombre/Siglas:</span> <span class="font-medium">${escapeHtml(usuario.empresa)}</span></div>
                        <div class="col-span-2"><span class="text-gray-500">Razón Social:</span> <span class="font-medium">${escapeHtml(usuario.nombre_juridico)}</span></div>
                        <div><span class="text-gray-500">Sector:</span> <span class="font-medium">${escapeHtml(usuario.sector)}</span></div>
                        <div><span class="text-gray-500">Nº Miembros:</span> <span class="font-medium">${escapeHtml(usuario.numero_miembros)}</span></div>
                        <div><span class="text-gray-500">Rep. Legal:</span> <span class="font-medium">${escapeHtml(usuario.representante_legal)}</span></div>
                        <div><span class="text-gray-500">Dir. Ejecutivo:</span> <span class="font-medium">${escapeHtml(usuario.director_ejecutivo)}</span></div>
                        <div><span class="text-gray-500">Constitución:</span> <span class="font-medium">${formatDate(usuario.inicio_actividades)}</span></div>
                    </div>
                </div>
            `;
        }

        detailsContainer.innerHTML = html;

        // Generar botones de acción
        const actionsContainer = document.getElementById('modal-actions');
        let currentStatus = usuario.estado;
        if (currentStatus == '1') currentStatus = 'activo';
        if (currentStatus == '0') currentStatus = 'inactivo';

        let buttonsHtml = '';

        // Botón Aprobar (Activo)
        if (currentStatus !== 'activo') {
            buttonsHtml += `
                <button onclick="confirmarEstado('activo')"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Aprobar
                </button>
            `;
        }

        // Botón Rechazar
        if (currentStatus !== 'rechazado') {
            buttonsHtml += `
                <button onclick="confirmarEstado('rechazado')"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Rechazar
                </button>
            `;
        }

        // Botón Pendiente
        if (currentStatus !== 'pendiente') {
            buttonsHtml += `
                <button onclick="confirmarEstado('pendiente')"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-xl hover:bg-yellow-100 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pendiente
                </button>
            `;
        }

        actionsContainer.innerHTML = buttonsHtml;
        document.getElementById('modalEstado').showModal();
    }

    async function confirmarEstado(nuevoEstado) {
        const id = document.getElementById('estado-usuario-id').value;
        // Enviar el estado como string ('activo', 'inactivo', 'rechazado', 'pendiente')
        // No convertir a 1/0 para evitar conflictos con el tipo de dato en BD si es VARCHAR/ENUM

        try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('estado', nuevoEstado);

            const response = await fetch('api/usuarios/cambiar_estado.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                document.getElementById('modalEstado').close();
                loadSolicitudes();
            } else {
                showToast('Error: ' + data.message, 'error');
            }
        } catch (error) {
            showToast('Error de conexión: ' + error.message, 'error');
        }
    }

    function getStatusBadge(estado) {
        if (estado == '1' || estado === 'activo') {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>';
        } else if (estado == '0' || estado === 'inactivo') {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactivo</span>';
        } else if (estado === 'pendiente') {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>';
        } else if (estado === 'rechazado') {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rechazado</span>';
        }
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">' + estado + '</span>';
    }

    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function escapeHtml(text) {
        if (!text && text !== 0) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.toString().replace(/[&<>"']/g, m => map[m]);
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-EC', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'bg-gray-900', 'bg-green-600', 'bg-red-600');
        if (type === 'success') toast.classList.add('bg-green-600');
        else if (type === 'error') toast.classList.add('bg-red-600');
        else toast.classList.add('bg-gray-900');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }
</script>

<?php include __DIR__ . '/components/footer.php'; ?>