<?php 
require_once __DIR__ . '/auth_middleware.php';
include __DIR__ . '/components/aside.php'; 
// La sesión ya debe estar iniciada en el aside o config
?>

<main class="min-h-screen p-6 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Redactar Nuevo Documento</h1>

        <form id="form-documento" class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento</label>
                    <select id="tipo_id" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border focus:ring-blue-500">
                        <option value="1">Memorando (MEM)</option>
                        <option value="2">Oficio (OFI)</option>
                        <option value="3">Circular (CIR)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                    <input type="text" id="asunto" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border focus:ring-blue-500" required placeholder="Ej: Solicitud de vacaciones">
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <label class="block text-sm font-bold text-blue-800 mb-2">Destinatarios</label>
                
                <div class="flex flex-wrap gap-2 mb-3">
                    <button type="button" onclick="abrirModal('directiva')" class="bg-purple-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-purple-700 flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Seleccionar Directiva
                    </button>
                    <button type="button" onclick="abrirModal('socios')" class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-blue-700 flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Seleccionar Socios
                    </button>
                </div>

                <div class="flex gap-2 mb-2">
                    <input type="email" id="input-email" class="flex-1 border-gray-300 rounded-lg p-2 border text-sm" placeholder="Escribir correo manual...">
                    <button onclick="agregarManual()" type="button" class="bg-gray-200 px-4 rounded-lg hover:bg-gray-300 text-sm font-medium">Añadir</button>
                </div>

                <div id="lista-destinatarios" class="flex flex-wrap gap-2 p-2 bg-white border rounded-lg min-h-[50px]"></div>
                <p class="text-xs text-gray-500 mt-1 text-right">Total destinatarios: <span id="contador-dest">0</span></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cuerpo del Documento</label>
                <textarea id="cuerpo" rows="10" class="w-full border-gray-300 rounded-lg shadow-sm p-3 border focus:ring-blue-500" placeholder="Escriba el contenido aquí..."></textarea>
            </div>

            <div class="p-4 border-2 border-dashed border-gray-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2 italic">Adjuntar archivos (PDF, Imágenes, etc.)</label>
                <input type="file" id="adjuntos" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex justify-end pt-4 border-t">
                <button type="submit" id="btn-enviar" class="bg-green-600 text-white px-10 py-3 rounded-lg hover:bg-green-700 transition flex items-center gap-2 font-bold shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Generar y Enviar Documento
                </button>
            </div>
        </form>
    </div>
</main>

<dialog id="modalUsuarios" class="p-0 rounded-xl shadow-2xl w-full max-w-2xl backdrop:bg-black/50">
    <div class="flex flex-col h-[80vh]">
        <div class="p-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-lg" id="modal-titulo">Seleccionar Destinatarios</h3>
            <button onclick="document.getElementById('modalUsuarios').close()" class="text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
        </div>
        <div class="p-4 border-b">
            <input type="text" id="buscador-modal" placeholder="Buscar por nombre o correo..." class="w-full p-2 border rounded-lg" onkeyup="filtrarModal()">
        </div>
        <div class="flex-1 overflow-y-auto p-4" id="lista-modal">
            </div>
        <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
            <button onclick="document.getElementById('modalUsuarios').close()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancelar</button>
            <button onclick="confirmarSeleccion()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">Agregar Seleccionados</button>
        </div>
    </div>
</dialog>

<script>
let destinatarios = [];
let usuariosCargados = [];
let documentoPadreId = null; // Tracking

// --- 1. LÓGICA DE RESPUESTA (AL CARGAR LA PÁGINA) ---
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const replyTo = urlParams.get('reply_to'); // Obtiene ?reply_to=ID

    if (replyTo) {
        console.log("Detectado modo respuesta para movimiento:", replyTo);
        try {
            // Llamada a la API de respuesta
            const res = await fetch(`api/documentos/obtener_datos_respuesta.php?mov_id=${replyTo}`);
            const result = await res.json();
            
            if (result.success) {
                const d = result.data;
                documentoPadreId = d.documento_id; // Guardamos el ID del documento padre

                // Llenar Asunto
                document.getElementById('asunto').value = "Re: " + d.asunto + " (" + d.codigo + ")";
                
                // Agregar al remitente original como destinatario
                // IMPORTANTE: Los nombres de campos 'email_respuesta' y 'nombre_completo' deben coincidir con tu API
                agregarDestinatario(d.email_respuesta, d.nombre_completo, d.remitente_id);
                
                // Mensaje visual de respuesta
                const alertBox = document.createElement('div');
                alertBox.className = "bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 text-yellow-700 text-sm";
                alertBox.innerHTML = `Está respondiendo al trámite <b>${d.codigo}</b>. El sistema mantendrá la trazabilidad.`;
                document.getElementById('form-documento').prepend(alertBox);
            }
        } catch (e) {
            console.error("Error cargando datos de respuesta:", e);
        }
    }
});

// --- 2. GESTIÓN DE CHIPS DE DESTINATARIOS ---
function agregarDestinatario(email, nombre = '', id = null) {
    if (!email) return;
    if (destinatarios.some(d => d.email === email)) return;
    
    destinatarios.push({ email, nombre, id });
    renderChips();
}

function renderChips() {
    const container = document.getElementById('lista-destinatarios');
    document.getElementById('contador-dest').textContent = destinatarios.length;
    container.innerHTML = destinatarios.map((d, i) => `
        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-2 border border-blue-200">
            ${d.nombre || 'Externo'} <${d.email}>
            <button type="button" onclick="eliminarDest(${i})" class="text-blue-500 hover:text-red-600 font-black">×</button>
        </span>
    `).join('');
}

function eliminarDest(index) {
    destinatarios.splice(index, 1);
    renderChips();
}

function agregarManual() {
    const input = document.getElementById('input-email');
    if (input.value.includes('@')) {
        agregarDestinatario(input.value.trim(), 'Externo', null);
        input.value = '';
    }
}

// --- 3. LÓGICA DEL MODAL ---
async function abrirModal(tipo) {
    const titulo = tipo === 'directiva' ? 'Seleccionar Directiva (SMTP)' : 'Seleccionar Socios';
    document.getElementById('modal-titulo').textContent = titulo;
    const lista = document.getElementById('lista-modal');
    lista.innerHTML = '<p class="text-center text-gray-500">Cargando...</p>';
    document.getElementById('modalUsuarios').showModal();

    try {
        const res = await fetch(`api/usuarios/listar_para_documentos.php?tipo=${tipo}`);
        const json = await res.json();
        usuariosCargados = json.data;
        pintarListaModal(usuariosCargados);
    } catch (e) {
        lista.innerHTML = '<p class="text-red-500">Error de conexión.</p>';
    }
}

function pintarListaModal(usuarios) {
    const lista = document.getElementById('lista-modal');
    lista.innerHTML = usuarios.map(u => `
        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer border-b">
            <input type="checkbox" value="${u.email}" 
                   data-nombre="${u.label_nombre || (u.nombre + ' ' + u.apellido)}" 
                   data-id="${u.id}" 
                   class="chk-modal w-5 h-5 text-blue-600 rounded">
            <div>
                <div class="font-bold text-gray-800">${u.label_nombre || (u.nombre + ' ' + u.apellido)}</div>
                <div class="text-xs text-gray-500">${u.email}</div>
            </div>
        </label>
    `).join('');
}

function confirmarSeleccion() {
    document.querySelectorAll('.chk-modal:checked').forEach(chk => {
        agregarDestinatario(chk.value, chk.dataset.nombre, chk.dataset.id);
    });
    document.getElementById('modalUsuarios').close();
}

function filtrarModal() {
    const texto = document.getElementById('buscador-modal').value.toLowerCase();
    const filtrados = usuariosCargados.filter(u => 
        (u.nombre + " " + u.apellido).toLowerCase().includes(texto) || u.email.toLowerCase().includes(texto)
    );
    pintarListaModal(filtrados);
}

// --- 4. ENVÍO DEL FORMULARIO ---
document.getElementById('form-documento').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (destinatarios.length === 0) return alert('Seleccione al menos un destinatario');

    const btn = document.getElementById('btn-enviar');
    const originalBtn = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Enviando trámite...';

    const formData = new FormData();
    formData.append('tipo_id', document.getElementById('tipo_id').value);
    formData.append('asunto', document.getElementById('asunto').value);
    formData.append('cuerpo', document.getElementById('cuerpo').value);
    formData.append('destinatarios', JSON.stringify(destinatarios));
    
    // Si es respuesta, enviamos el ID del padre
    if (documentoPadreId) {
        formData.append('documento_padre_id', documentoPadreId);
    }

    // Adjuntar archivos
    const inputFiles = document.getElementById('adjuntos').files;
    for (let i = 0; i < inputFiles.length; i++) {
        formData.append('archivos[]', inputFiles[i]);
    }

    try {
        const res = await fetch('api/documentos/crear.php', { method: 'POST', body: formData });
        const json = await res.json();

        if (json.success) {
            alert('Documento enviado: ' + json.codigo);
            window.location.href = 'bandeja_salida.php';
        } else {
            alert('Error: ' + json.message);
        }
    } catch (e) {
        alert('Error de red.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalBtn;
    }
});
</script>