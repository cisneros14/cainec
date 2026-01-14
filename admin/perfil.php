<?php
// Proteger la página - todos los roles autenticados
require_once __DIR__ . '/auth_middleware.php';
// No se restringe por rol, cualquier usuario autenticado puede ver su perfil

// Obtener información completa del usuario desde la BD
require_once __DIR__ . '/../config.php';

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$pdo = new PDO($dsn, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$rolNombre = getNombreRol($usuario['rol']);

include __DIR__ . '/components/aside.php';
?>

<main class="min-h-screen p-0 md:p-6">
    <div class="w-full max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mi Perfil</h1>
                <p class="text-gray-500 text-sm mt-1">Administra tu información personal y de contacto</p>
            </div>
            <div class="flex gap-2">
                <button
                    id="btn-editar"
                    onclick="habilitarEdicion()"
                    class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Perfil
                </button>
                <button
                    id="btn-guardar"
                    onclick="guardarPerfil()"
                    class="hidden bg-green-600 hover:bg-green-700 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Cambios
                </button>
                <button
                    id="btn-cancelar"
                    onclick="cancelarEdicion()"
                    class="hidden bg-gray-500 hover:bg-gray-600 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>

        <!-- Alertas -->
        <div id="alert-container" class="mb-4"></div>

        <!-- Formulario de Perfil -->
        <form id="form-perfil" class="space-y-6">
            
            <!-- Foto de Perfil -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Foto de Perfil
                </h2>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <?php 
                        $imgUrl = $usuario['img_url'];
                        if ($imgUrl === 'undefined' || empty($imgUrl)) {
                            $imgUrl = 'assets/images/recursos/placeholder.webp';
                        }
                        ?>
                        <img 
                            id="preview-img" 
                            src="../<?php echo htmlspecialchars($imgUrl); ?>" 
                            alt="Foto de perfil"
                            onerror="this.onerror=null;this.src='../assets/images/recursos/placeholder.webp'" 
                            class="w-32 h-32 rounded-full object-cover border-4 border-gray-200"
                        >
                        <input type="hidden" id="img_url" name="img_url" value="<?php echo htmlspecialchars($usuario['img_url'] ?? ''); ?>">
                    </div>
                    <div>
                        <input 
                            type="file" 
                            id="input-imagen" 
                            accept="image/*" 
                            class="hidden" 
                            disabled
                            onchange="handleImageUpload(event)"
                        >
                        <button 
                            type="button" 
                            id="btn-cambiar-foto"
                            onclick="document.getElementById('input-imagen').click()"
                            disabled
                            class="bg-gray-100 text-gray-400 px-4 py-2 rounded-lg font-medium cursor-not-allowed"
                        >
                            Cambiar foto
                        </button>
                        <p class="text-sm text-gray-500 mt-2">JPG, PNG o WEBP. Máximo 5MB.</p>
                    </div>
                </div>
            </div>

            <!-- Datos Personales -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Datos Personales
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" id="nombre" required maxlength="20"
                            value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido <span class="text-red-500">*</span></label>
                        <input type="text" name="apellido" id="apellido" required maxlength="20"
                            value="<?php echo htmlspecialchars($usuario['apellido']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cédula/RUC</label>
                        <input type="text" name="cedula_ruc" id="cedula_ruc" maxlength="15"
                            value="<?php echo htmlspecialchars($usuario['cedula_ruc']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                        <input type="text" name="cargo" id="cargo"
                            value="<?php echo htmlspecialchars($usuario['cargo']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" disabled readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empresa</label>
                        <input type="text" name="empresa" id="empresa" maxlength="100"
                            value="<?php echo htmlspecialchars($usuario['empresa']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Licencia</label>
                        <input type="text" name="licencia" id="licencia" maxlength="100"
                            value="<?php echo htmlspecialchars($usuario['licencia']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                </div>
            </div>

            <!-- Datos de Contacto -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Datos de Contacto
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono Principal</label>
                        <input type="tel" name="telefono_contacto" id="telefono_contacto"
                            value="<?php echo htmlspecialchars($usuario['telefono_contacto']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono Secundario</label>
                        <input type="tel" name="telefono_contacto2" id="telefono_contacto2"
                            value="<?php echo htmlspecialchars($usuario['telefono_contacto2']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                        <input type="text" name="direccion" id="direccion"
                            value="<?php echo htmlspecialchars($usuario['direccion']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                        <input type="text" name="provincia" id="provincia" maxlength="100"
                            value="<?php echo htmlspecialchars($usuario['provincia']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                        <input type="text" name="ciudad" id="ciudad" maxlength="100"
                            value="<?php echo htmlspecialchars($usuario['ciudad']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Página Web</label>
                        <input type="url" name="pagina_web" id="pagina_web"
                            value="<?php echo htmlspecialchars($usuario['pagina_web']); ?>"
                            placeholder="https://ejemplo.com"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Redes Sociales
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                        <input type="url" name="facebook" id="facebook"
                            value="<?php echo htmlspecialchars($usuario['facebook']); ?>"
                            placeholder="https://facebook.com/tu-perfil"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                        <input type="url" name="instagram" id="instagram"
                            value="<?php echo htmlspecialchars($usuario['instagram']); ?>"
                            placeholder="https://instagram.com/tu-perfil"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                        <input type="url" name="linkedin" id="linkedin"
                            value="<?php echo htmlspecialchars($usuario['linkedin']); ?>"
                            placeholder="https://linkedin.com/in/tu-perfil"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                    </div>
                </div>
            </div>

            <!-- Datos de Sistema (Solo lectura) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Datos del Sistema (Solo lectura)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['usuario']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" disabled readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($usuario['email']); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" disabled readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                        <input type="text" value="<?php echo htmlspecialchars($rolNombre); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" disabled readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <input type="text" value="<?php echo $usuario['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" disabled readonly>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Información Adicional (Opcional)
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed resize-none" 
                            disabled><?php echo htmlspecialchars($usuario['descripcion'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Formación</label>
                        <textarea name="formacion" id="formacion" rows="3"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed resize-none" 
                            disabled><?php echo htmlspecialchars($usuario['formacion'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Habilidades</label>
                        <textarea name="habilidades" id="habilidades" rows="3"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed resize-none" 
                            disabled><?php echo htmlspecialchars($usuario['habilidades'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificaciones</label>
                        <textarea name="certificaciones" id="certificaciones" rows="3"
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed resize-none" 
                            disabled><?php echo htmlspecialchars($usuario['certificaciones'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

        </form>
    </div>
</main>

<script>
let editando = false;
const datosOriginales = {};

// Guardar datos originales al cargar
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-perfil');
    const inputs = form.querySelectorAll('input:not([type="file"]):not([readonly]), textarea');
    inputs.forEach(input => {
        datosOriginales[input.name] = input.value;
    });
});

function habilitarEdicion() {
    editando = true;
    
    // Cambiar botones
    document.getElementById('btn-editar').classList.add('hidden');
    document.getElementById('btn-guardar').classList.remove('hidden');
    document.getElementById('btn-guardar').classList.add('flex');
    document.getElementById('btn-cancelar').classList.remove('hidden');
    document.getElementById('btn-cancelar').classList.add('flex');
    
    // Habilitar campos editables (excepto los de sistema)
    const form = document.getElementById('form-perfil');
    const inputs = form.querySelectorAll('.form-input:not([readonly])');
    
    inputs.forEach(input => {
        input.disabled = false;
        input.classList.remove('bg-gray-50', 'text-gray-500', 'cursor-not-allowed');
        input.classList.add('bg-white', 'text-gray-900', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-transparent');
    });
    
    // Habilitar botón de cambiar foto y el input hidden de img_url
    document.getElementById('input-imagen').disabled = false;
    // Asegurar que el input hidden de la URL de imagen no esté deshabilitado si lo estaba
    const imgUrlInput = document.getElementById('img_url');
    if (imgUrlInput) imgUrlInput.disabled = false;

    const btnFoto = document.getElementById('btn-cambiar-foto');
    btnFoto.disabled = false;
    btnFoto.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
    btnFoto.classList.add('bg-blue-500', 'hover:bg-blue-600', 'text-white', 'cursor-pointer');
    btnFoto.textContent = 'Cambiar foto';
}

function cancelarEdicion() {
    editando = false;
    
    // Restaurar botones
    document.getElementById('btn-editar').classList.remove('hidden');
    document.getElementById('btn-guardar').classList.add('hidden');
    document.getElementById('btn-cancelar').classList.add('hidden');
    
    // Restaurar valores originales
    const form = document.getElementById('form-perfil');
    const inputs = form.querySelectorAll('input:not([type="file"]):not([readonly]), textarea');
    
    inputs.forEach(input => {
        if (datosOriginales.hasOwnProperty(input.name)) {
            input.value = datosOriginales[input.name];
        }
        // No deshabilitar inputs hidden para evitar problemas al enviar
        if (input.type !== 'hidden') {
            input.disabled = true;
            input.classList.add('bg-gray-50', 'text-gray-500', 'cursor-not-allowed');
            input.classList.remove('bg-white', 'text-gray-900', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-transparent');
        }
    });
    
    // Deshabilitar botón de cambiar foto
    document.getElementById('input-imagen').disabled = true;
    const btnFoto = document.getElementById('btn-cambiar-foto');
    btnFoto.disabled = true;
    btnFoto.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
    btnFoto.classList.remove('bg-blue-500', 'hover:bg-blue-600', 'text-white', 'cursor-pointer');
    btnFoto.textContent = 'Cambiar foto';
}

async function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validar tipo y tamaño
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        mostrarAlerta('Solo se permiten imágenes JPG, PNG o WEBP', 'error');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) {
        mostrarAlerta('La imagen no debe superar los 5MB', 'error');
        return;
    }
    
    // Subir imagen
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
            mostrarAlerta('Imagen cargada. No olvides guardar los cambios.', 'success');
        } else {
            mostrarAlerta(data.message, 'error');
        }
    } catch (error) {
        console.error('Error subiendo imagen:', error);
        mostrarAlerta('Error al subir la imagen', 'error');
    }
}

async function guardarPerfil() {
    const form = document.getElementById('form-perfil');
    const formData = new FormData();
    
    // Agregar ID del usuario
    formData.append('id', <?php echo $_SESSION['user_id']; ?>);
    
    // Agregar todos los campos
    const inputs = form.querySelectorAll('input:not([type="file"]):not([readonly]), textarea');
    inputs.forEach(input => {
        if (input.name) {
            // Asegurarse de enviar el valor actual, incluso si está deshabilitado (aunque ya los habilitamos)
            formData.append(input.name, input.value);
        }
    });
    
    console.log('Enviando datos de perfil:', Object.fromEntries(formData));

    // Validaciones
    if (!formData.get('nombre') || !formData.get('apellido')) {
        mostrarAlerta('El nombre y apellido son obligatorios', 'error');
        return;
    }
    
    try {
        const response = await fetch('api/usuarios/editar.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            mostrarAlerta('Perfil actualizado exitosamente', 'success');
            
            // Actualizar datos originales
            inputs.forEach(input => {
                if (input.name) {
                    datosOriginales[input.name] = input.value;
                }
            });
            
            // Actualizar sesión si cambió nombre o apellido
            if (formData.get('nombre') || formData.get('apellido')) {
                // Recargar para actualizar la sesión
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                cancelarEdicion();
            }
        } else {
            mostrarAlerta(data.message, 'error');
        }
    } catch (error) {
        console.error('Error guardando perfil:', error);
        mostrarAlerta('Error al actualizar el perfil', 'error');
    }
}

function mostrarAlerta(mensaje, tipo) {
    const container = document.getElementById('alert-container');
    const colores = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    container.innerHTML = `
        <div class="border ${colores[tipo]} px-4 py-3 rounded-lg">
            ${mensaje}
        </div>
    `;
    
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}
</script>

<?php include __DIR__ . '/components/footer.php'; ?>
