<?php 
require_once __DIR__ . '/auth_middleware.php';
include __DIR__ . '/components/aside.php'; ?>

<main class="min-h-screen p-6 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Bandeja de Entrada</h1>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold" id="total-docs">0 Documentos</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Buscar por Asunto / Código</label>
                <input type="text" id="busqueda" onkeyup="filtrarTabla()" placeholder="Escriba para buscar..." class="w-full border-gray-300 rounded-lg p-2 border">
            </div>
            <div class="w-48">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipo de Doc.</label>
                <select id="filtro-tipo" onchange="filtrarTabla()" class="w-full border-gray-300 rounded-lg p-2 border">
                    <option value="">Todos</option>
                    <option value="MEM">Memorando</option>
                    <option value="OFI">Oficio</option>
                    <option value="CIR">Circular</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Remitente</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Documento</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Fecha</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-cuerpo">
                    </tbody>
            </table>
        </div>
    </div>
</main>

<dialog id="modalHistorial" class="p-0 rounded-xl shadow-2xl w-full max-w-lg backdrop:bg-black/50">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl text-gray-800">Historial del Trámite</h3>
            <button onclick="document.getElementById('modalHistorial').close()" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
        </div>
        <div id="contenido-historial" class="space-y-6 relative border-l-2 border-blue-100 ml-3">
            </div>
    </div>
</dialog>

<script>
let documentosFull = []; // Cache de datos

async function cargarBandeja() {
    const res = await fetch('api/documentos/listar_recibidos.php');
    const json = await res.json();
    if(json.success) {
        documentosFull = json.data;
        pintarTabla(documentosFull);
    }
}

function pintarTabla(lista) {
    const cuerpo = document.getElementById('tabla-cuerpo');
    document.getElementById('total-docs').textContent = lista.length + " Documentos";
    
    cuerpo.innerHTML = lista.map(doc => `
        <tr class="border-b hover:bg-gray-50 transition ${doc.leido == 0 ? 'bg-blue-50/50 font-semibold' : ''}">
            <td class="p-4">
                <div class="text-sm text-gray-900">${doc.remitente_nombre}</div>
                <div class="text-xs text-gray-500">Remitente</div>
            </td>
            <td class="p-4">
                <div class="text-blue-700 font-mono text-xs">${doc.codigo}</div>
                <div class="text-sm text-gray-800">${doc.asunto}</div>
            </td>
            <td class="p-4 text-sm text-gray-600">
                ${doc.fecha_envio} </td>
            <td class="p-4">
                <div class="flex justify-center gap-2">
                    <a href="ver_documento_interno.php?id=${doc.movimiento_id}" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 shadow-sm" title="Leer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <button onclick="verHistorial(${doc.documento_id})" class="bg-gray-100 text-gray-600 p-2 rounded-lg hover:bg-gray-200" title="Ver Historial">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function filtrarTabla() {
    const busqueda = document.getElementById('busqueda').value.toLowerCase();
    const tipo = document.getElementById('filtro-tipo').value; // MEM, OFI, etc

    const filtrados = documentosFull.filter(doc => {
        const coincideTexto = doc.asunto.toLowerCase().includes(busqueda) || doc.codigo.toLowerCase().includes(busqueda);
        const coincideTipo = tipo === "" || doc.codigo.startsWith(tipo);
        return coincideTexto && coincideTipo;
    });
    
    pintarTabla(filtrados);
}

async function verHistorial(docId) {
    const modal = document.getElementById('modalHistorial');
    const contenedor = document.getElementById('contenido-historial');
    contenedor.innerHTML = '<div class="p-10 text-center">Cargando hilo de conversación...</div>';
    modal.showModal();

    try {
        const res = await fetch(`api/documentos/obtener_historial.php?documento_id=${docId}`);
        const json = await res.json();

        if(json.success && json.data.length > 0) {
            contenedor.innerHTML = json.data.map(h => `
                <div class="pl-8 relative border-l-2 border-blue-200 pb-6 last:pb-0 ml-2">
                    <span class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-white ${h.accion === 'LEIDO' ? 'bg-green-500' : 'bg-blue-600'}"></span>
                    
                    <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                        <p class="text-xs font-mono text-blue-600 font-bold">${h.doc_codigo}</p>
                        <p class="text-sm font-bold text-gray-800">${h.remitente_nombre}</p>
                        <p class="text-[10px] text-gray-400">${h.fecha_envio}</p>
                        
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-[10px] uppercase font-black ${h.accion === 'LEIDO' ? 'text-green-600' : 'text-blue-600'}">
                                ${h.accion}
                            </span>
                            ${h.fecha_lectura ? `<span class="text-[10px] text-gray-400 italic">Visto: ${h.fecha_lectura}</span>` : ''}
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">Hacia: ${h.destinatario_email}</p>
                    </div>
                </div>
            `).join('');
        } else {
            contenedor.innerHTML = '<p class="text-center text-gray-500">No hay movimientos registrados para este documento.</p>';
        }
    } catch (e) {
        contenedor.innerHTML = '<p class="text-red-500 text-center">Error al cargar el historial.</p>';
    }
}

document.addEventListener('DOMContentLoaded', cargarBandeja);
</script>