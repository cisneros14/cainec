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
                <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
                <p class="text-gray-500 text-sm mt-1">Administra los usuarios del sistema</p>
            </div>
            <button onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo usuario
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" id="filtro-nombre" placeholder="Buscar por nombre..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                <input type="text" id="filtro-email" placeholder="Buscar por email..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                <button onclick="limpiarFiltros()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usuarios-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay usuarios</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo usuario.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-4xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto">
    <form id="formUsuario" class="p-6 space-y-5">
        <input type="hidden" id="usuario_id" name="id">

        <h2 id="modal-title" class="text-xl font-semibold text-gray-900 border-b pb-3">Nuevo usuario</h2>

        <!-- Foto de perfil -->
        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                Foto de perfil
            </label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, WEBP. Tamaño máximo: 5MB</p>
            <input type="hidden" id="img_url" name="img_url">
            <div id="preview-foto" class="mt-3 hidden">
                <p class="text-xs text-gray-600 mb-1">Vista previa:</p>
                <img id="preview-img" src="" alt="Vista previa"
                    class="h-32 w-32 rounded-full object-cover border-2 shadow-sm">
            </div>
        </div>

        <!-- Datos personales -->
        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase">Datos Personales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre" required maxlength="20"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: Juan" />
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="apellido" name="apellido" required maxlength="20"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: Pérez" />
                </div>

                <div>
                    <label for="cedula_ruc" class="block text-sm font-medium text-gray-700 mb-2">
                        Cédula/RUC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="cedula_ruc" name="cedula_ruc" required maxlength="15"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: 1234567890" />
                </div>

                <div>
                    <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo
                    </label>
                    <input type="text" id="cargo" name="cargo" maxlength="1500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: Gerente General" />
                </div>

                <div>
                    <label for="empresa" class="block text-sm font-medium text-gray-700 mb-2">
                        Empresa
                    </label>
                    <input type="text" id="empresa" name="empresa" maxlength="100"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: CAINEC" />
                </div>

                <div>
                    <label for="licencia" class="block text-sm font-medium text-gray-700 mb-2">
                        Licencia
                    </label>
                    <input type="text" id="licencia" name="licencia" maxlength="100"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: LIC-2024-001" />
                </div>
            </div>
        </div>

        <!-- Datos de contacto -->
        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase">Datos de Contacto</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="telefono_contacto" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono principal
                    </label>
                    <input type="tel" id="telefono_contacto" name="telefono_contacto" maxlength="500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: +593 999 999 999" />
                </div>

                <div>
                    <label for="telefono_contacto2" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono secundario
                    </label>
                    <input type="tel" id="telefono_contacto2" name="telefono_contacto2" maxlength="500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: +593 888 888 888" />
                </div>

                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección
                    </label>
                    <input type="text" id="direccion" name="direccion" maxlength="1500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: Av. Principal 123 y Calle Secundaria" />
                </div>

                <div>
                    <label for="provincia" class="block text-sm font-medium text-gray-700 mb-2">
                        Provincia
                    </label>
                    <input type="text" id="provincia" name="provincia" maxlength="100"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: Pichincha" />
                </div>

                <div>
                    <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-2">
                        Ciudad
                    </label>
                    <input type="text" id="ciudad" name="ciudad" maxlength="100"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: Quito" />
                </div>

                <div class="md:col-span-2">
                    <label for="pagina_web" class="block text-sm font-medium text-gray-700 mb-2">
                        Página web
                    </label>
                    <input type="url" id="pagina_web" name="pagina_web" maxlength="1500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: https://www.ejemplo.com" />
                </div>
            </div>
        </div>

        <!-- Redes sociales -->
        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase">Redes Sociales</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">
                        Facebook
                    </label>
                    <input type="url" id="facebook" name="facebook" maxlength="1500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="URL de Facebook" />
                </div>

                <div>
                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">
                        Instagram
                    </label>
                    <input type="url" id="instagram" name="instagram" maxlength="1500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="URL de Instagram" />
                </div>

                <div>
                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">
                        LinkedIn
                    </label>
                    <input type="url" id="linkedin" name="linkedin" maxlength="1500"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="URL de LinkedIn" />
                </div>
            </div>
        </div>

        <!-- Datos de sistema -->
        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase">Datos de Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="usuario" class="block text-sm font-medium text-gray-700 mb-2">
                        Usuario <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="usuario" name="usuario" required maxlength="64"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: jperez" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required maxlength="64"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ej: juan@ejemplo.com" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña <span id="password-required" class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" minlength="6"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Mínimo 6 caracteres" />
                    <p id="password-hint" class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
                </div>

                <div>
                    <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                        Rol <span class="text-red-500">*</span>
                    </label>
                    <select id="rol" name="rol" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="0">Seleccione un rol</option>
                        <option value="1">Administrador</option>
                        <option value="2">Socio</option>
                        <option value="10">Presidente</option>
                        <option value="11">Vicepresidente</option>
                        <option value="12">Secretaria</option>
                        <option value="20">Delegado</option>
                    </select>
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <select id="estado" name="estado"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label for="directiva" class="block text-sm font-medium text-gray-700 mb-2">
                        Es parte de la directiva
                    </label>
                    <select id="directiva" name="directiva"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase">Información Adicional (Opcional)</h3>
            <div class="space-y-4">
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        placeholder="Breve descripción del usuario..."></textarea>
                </div>

                <div>
                    <label for="formacion" class="block text-sm font-medium text-gray-700 mb-2">
                        Formación académica
                    </label>
                    <textarea id="formacion" name="formacion" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        placeholder="Títulos, estudios, etc..."></textarea>
                </div>

                <div>
                    <label for="habilidades" class="block text-sm font-medium text-gray-700 mb-2">
                        Habilidades
                    </label>
                    <textarea id="habilidades" name="habilidades" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        placeholder="Habilidades profesionales..."></textarea>
                </div>

                <div>
                    <label for="certificaciones" class="block text-sm font-medium text-gray-700 mb-2">
                        Certificaciones
                    </label>
                    <textarea id="certificaciones" name="certificaciones" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        placeholder="Certificaciones obtenidas..."></textarea>
                </div>
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
                <h2 class="text-xl font-semibold text-gray-900">Eliminar usuario</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar al usuario <strong id="delete-usuario-nombre"></strong>?
            Esta acción no se puede deshacer.
        </p>

        <input type="hidden" id="delete-usuario-id">

        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="document.getElementById('modalDelete').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                Cancelar
            </button>
            <button onclick="confirmarEliminar()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                Eliminar
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
    let usuariosData = [];

    // Cargar usuarios al iniciar
    document.addEventListener('DOMContentLoaded', () => {
        loadUsuarios();

        // Event listeners para filtros
        document.getElementById('filtro-nombre').addEventListener('input', aplicarFiltros);
        document.getElementById('filtro-email').addEventListener('input', aplicarFiltros);

        // Event listener para el formulario
        document.getElementById('formUsuario').addEventListener('submit', handleSubmit);

        // Event listener para el input de foto
        document.getElementById('foto').addEventListener('change', handleImageUpload);
    });

    // Cargar usuarios desde la API
    async function loadUsuarios() {
        try {
            const response = await fetch('api/usuarios/listar.php');
            const data = await response.json();

            if (data.success) {
                usuariosData = data.data;
                renderUsuarios(usuariosData);
            } else {
                showToast('Error al cargar usuarios: ' + data.message, 'error');
            }
        } catch (error) {
            showToast('Error de conexión: ' + error.message, 'error');
        }
    }

    // Renderizar tabla de usuarios
    function renderUsuarios(usuarios) {
        const tbody = document.getElementById('usuarios-tbody');
        const emptyState = document.getElementById('empty-state');

        if (usuarios.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        tbody.innerHTML = usuarios.map(usuario => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${usuario.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${usuario.img_url ?
                `<img src="../${usuario.img_url}" alt="${escapeHtml(usuario.nombre)}" class="h-10 w-10 rounded-full object-cover">` :
                `<div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>`
            }
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${escapeHtml(usuario.nombre)} ${escapeHtml(usuario.apellido)}</div>
                <div class="text-sm text-gray-500">${escapeHtml(usuario.cargo || '-')}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(usuario.usuario)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(usuario.email)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(usuario.empresa || '-')}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${usuario.estado == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            }">
                    ${usuario.estado == 1 ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                    onclick='openEditModal(${JSON.stringify(usuario).replace(/'/g, "&apos;")})'
                    class="text-blue-600 hover:text-blue-900 mr-3"
                    title="Editar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                <button
                    onclick="openDeleteModal(${usuario.id}, '${escapeHtml(usuario.nombre)} ${escapeHtml(usuario.apellido)}')"
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
    }

    // Aplicar filtros
    function aplicarFiltros() {
        const filtroNombre = document.getElementById('filtro-nombre').value.toLowerCase();
        const filtroEmail = document.getElementById('filtro-email').value.toLowerCase();

        const usuariosFiltrados = usuariosData.filter(usuario => {
            const nombreCompleto = `${usuario.nombre} ${usuario.apellido}`.toLowerCase();
            const matchNombre = filtroNombre === '' || nombreCompleto.includes(filtroNombre);
            const matchEmail = filtroEmail === '' || usuario.email.toLowerCase().includes(filtroEmail);

            return matchNombre && matchEmail;
        });

        renderUsuarios(usuariosFiltrados);
    }

    // Limpiar filtros
    function limpiarFiltros() {
        document.getElementById('filtro-nombre').value = '';
        document.getElementById('filtro-email').value = '';
        renderUsuarios(usuariosData);
    }

    // Abrir modal de crear
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Nuevo usuario';
        document.getElementById('formUsuario').reset();
        document.getElementById('usuario_id').value = '';
        document.getElementById('img_url').value = '';
        document.getElementById('preview-foto').classList.add('hidden');
        document.getElementById('password-required').classList.remove('hidden');
        document.getElementById('password').required = true;
        document.getElementById('password-hint').textContent = 'Mínimo 6 caracteres (Requerido)';
        document.getElementById('modalForm').showModal();
    }

    // Abrir modal de editar
    function openEditModal(usuario) {
        document.getElementById('modal-title').textContent = 'Editar usuario';
        document.getElementById('usuario_id').value = usuario.id;
        document.getElementById('nombre').value = usuario.nombre;
        document.getElementById('apellido').value = usuario.apellido;
        document.getElementById('cedula_ruc').value = usuario.cedula_ruc;
        document.getElementById('cargo').value = usuario.cargo || '';
        document.getElementById('empresa').value = usuario.empresa || '';
        document.getElementById('licencia').value = usuario.licencia || '';
        document.getElementById('telefono_contacto').value = usuario.telefono_contacto || '';
        document.getElementById('telefono_contacto2').value = usuario.telefono_contacto2 || '';
        document.getElementById('direccion').value = usuario.direccion || '';
        document.getElementById('provincia').value = usuario.provincia || '';
        document.getElementById('ciudad').value = usuario.ciudad || '';
        document.getElementById('pagina_web').value = usuario.pagina_web || '';
        document.getElementById('facebook').value = usuario.facebook || '';
        document.getElementById('instagram').value = usuario.instagram || '';
        document.getElementById('linkedin').value = usuario.linkedin || '';
        document.getElementById('usuario').value = usuario.usuario;
        document.getElementById('email').value = usuario.email;
        document.getElementById('password').value = '';
        document.getElementById('rol').value = usuario.rol;
        document.getElementById('estado').value = usuario.estado;
        document.getElementById('directiva').value = usuario.directiva;
        document.getElementById('img_url').value = usuario.img_url || '';
        document.getElementById('descripcion').value = usuario.descripcion || '';
        document.getElementById('formacion').value = usuario.formacion || '';
        document.getElementById('habilidades').value = usuario.habilidades || '';
        document.getElementById('certificaciones').value = usuario.certificaciones || '';

        // Mostrar preview si hay imagen
        if (usuario.img_url) {
            document.getElementById('preview-img').src = '../' + usuario.img_url;
            document.getElementById('preview-foto').classList.remove('hidden');
        } else {
            document.getElementById('preview-foto').classList.add('hidden');
        }

        // Hacer contraseña opcional en edición
        document.getElementById('password-required').classList.add('hidden');
        document.getElementById('password').required = false;
        document.getElementById('password-hint').textContent = 'Dejar vacío para mantener la contraseña actual';

        document.getElementById('modalForm').showModal();
    }

    // Subir imagen
    async function handleImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('imagen', file);

        try {
            const response = await fetch('api/usuarios/subir-imagen.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('img_url').value = data.img_url;
                document.getElementById('preview-img').src = '../' + data.img_url;
                document.getElementById('preview-foto').classList.remove('hidden');
                showToast('Imagen subida exitosamente', 'success');
            } else {
                showToast('Error al subir imagen: ' + data.message, 'error');
            }
        } catch (error) {
            showToast('Error de conexión: ' + error.message, 'error');
        }
    }

    // Enviar formulario
    async function handleSubmit(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const usuarioId = document.getElementById('usuario_id').value;
        const url = usuarioId ? 'api/usuarios/editar.php' : 'api/usuarios/crear.php';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                document.getElementById('modalForm').close();
                loadUsuarios();
            } else {
                showToast('Error: ' + data.message, 'error');
            }
        } catch (error) {
            showToast('Error de conexión: ' + error.message, 'error');
        }
    }

    // Abrir modal de eliminar
    function openDeleteModal(id, nombre) {
        document.getElementById('delete-usuario-id').value = id;
        document.getElementById('delete-usuario-nombre').textContent = nombre;
        document.getElementById('modalDelete').showModal();
    }

    // Confirmar eliminación
    async function confirmarEliminar() {
        const id = document.getElementById('delete-usuario-id').value;

        try {
            const formData = new FormData();
            formData.append('id', id);

            const response = await fetch('api/usuarios/eliminar.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                document.getElementById('modalDelete').close();
                loadUsuarios();
            } else {
                showToast('Error: ' + data.message, 'error');
            }
        } catch (error) {
            showToast('Error de conexión: ' + error.message, 'error');
        }
    }

    // Mostrar toast
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

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    // Escapar HTML
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

    // Formatear fecha
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-EC', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    // Obtener nombre del rol
    function getRolNombre(rol) {
        const roles = {
            1: 'Administrador',
            2: 'Socio',
            10: 'Presidente',
            11: 'Vicepresidente',
            12: 'Secretaria',
            20: 'Delegado'
        };
        return roles[rol] || 'Desconocido';
    }
</script>

<?php include __DIR__ . '/components/footer.php'; ?>