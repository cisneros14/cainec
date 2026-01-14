<?php
// admin/ver_documento_interno.php
require_once __DIR__ . '/../../../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// 1. SEGURIDAD
$usuario_id = $_SESSION['user_id'] ?? 0;
$movimiento_id = $_GET['id'] ?? 0;

if ($usuario_id === 0 || $movimiento_id === 0) {
    header("Location: bandeja_entrada.php");
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-05:00'"
    ]);

    // 2. OBTENER DETALLES DEL DOCUMENTO Y VALIDAR DESTINATARIO
    // Unimos movimientos con documentos y el usuario remitente
    $sql = "
        SELECT 
            d.*, 
            m.id as mov_id, m.destinatario_id, m.destinatario_email, m.accion,
            CONCAT(u.nombre, ' ', u.apellido) as remitente_nombre,
            u.email as remitente_email,
            td.nombre as tipo_nombre
        FROM movimientos m
        INNER JOIN documentos d ON m.documento_id = d.id
        INNER JOIN usuarios u ON d.creador_id = u.id
        INNER JOIN tipos_documento td ON d.tipo_id = td.id
        WHERE m.id = ?
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$movimiento_id]);
    $doc = $stmt->fetch(PDO::FETCH_ASSOC);

    // Seguridad: Verificar que el documento sea para el usuario logueado
    // (Buscamos por ID o por Email por si es un registro externo)
    $stmtMe = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
    $stmtMe->execute([$usuario_id]);
    $mi_email = $stmtMe->fetchColumn();

    if (!$doc || ($doc['destinatario_id'] != $usuario_id && $doc['destinatario_email'] != $mi_email)) {
        die("No tiene permiso para ver este documento.");
    }

    // 3. MARCAR COMO LEÍDO (Si aún no lo está)
    if ($doc['accion'] !== 'LEIDO') {
        $update = $pdo->prepare("UPDATE movimientos SET accion = 'LEIDO', fecha_lectura = NOW() WHERE id = ?");
        $update->execute([$movimiento_id]);
    }

    // 4. OBTENER ADJUNTOS
    $stmtAdj = $pdo->prepare("SELECT * FROM adjuntos WHERE documento_id = ?");
    $stmtAdj->execute([$doc['id']]);
    $adjuntos = $stmtAdj->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

include __DIR__ . '/components/aside.php'; 
?>

<main class="min-h-screen p-4 sm:p-6 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <a href="bandeja_entrada.php" class="flex items-center text-blue-600 hover:text-blue-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver a la bandeja
            </a>
            
            <button onclick="window.print()" class="bg-gray-200 p-2 rounded-lg hover:bg-gray-300 transition" title="Imprimir">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2z"/></svg>
            </button>
        </div>

        <div class="bg-white shadow-xl rounded-sm overflow-hidden border border-gray-200 mb-8" id="documento-imprimible">
            
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-blue-900 font-bold text-xl"><?php echo strtoupper($doc['tipo_nombre']); ?></h2>
                        <p class="text-gray-500 font-mono text-lg"><?php echo $doc['codigo']; ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Fecha de Emisión</p>
                        <p class="font-medium"><?php echo date('d/m/Y H:i', strtotime($doc['fecha_creacion'])); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 uppercase text-xs font-bold">De:</p>
                        <p class="font-semibold text-gray-800"><?php echo $doc['remitente_nombre']; ?></p>
                        <p class="text-gray-500"><?php echo $doc['remitente_email']; ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 uppercase text-xs font-bold">Asunto:</p>
                        <p class="font-semibold text-gray-800"><?php echo $doc['asunto']; ?></p>
                    </div>
                </div>
            </div>

            <div class="p-10 min-h-[400px]">
                <div class="prose max-w-none text-gray-800 leading-relaxed">
                    <?php echo nl2br($doc['cuerpo']); ?>
                </div>
            </div>

            <div class="p-10 pt-0">
                <div class="mt-20 w-64 border-t border-gray-300 text-center">
                    <p class="mt-2 font-bold text-gray-800"><?php echo $doc['remitente_nombre']; ?></p>
                    <p class="text-xs text-gray-500">Firmado Electrónicamente</p>
                </div>
            </div>
        </div>

        <?php if (count($adjuntos) > 0): ?>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 mb-8">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Archivos Adjuntos (<?php echo count($adjuntos); ?>)
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php foreach ($adjuntos as $adj): ?>
                    <a href="../<?php echo $adj['ruta_archivo']; ?>" target="_blank" class="flex items-center p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-200 transition group">
                        <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 transition">
                            <svg class="w-6 h-6 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-medium text-gray-700 truncate"><?php echo $adj['nombre_original']; ?></p>
                            <p class="text-xs text-gray-400">Haga clic para descargar</p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="flex gap-4">
            <button onclick="responder()" class="flex-1 bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition shadow-lg flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Responder Documento
            </button>
            <button class="bg-white text-gray-600 font-bold py-3 px-6 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Archivar
            </button>
        </div>

    </div>
</main>

<script>
function responder() {
    // Redirigir a nuevo documento pasando el ID padre y los datos básicos
    // Esto lo usaremos luego para que el sistema sepa que es una respuesta
    const idPadre = "<?php echo $doc['id']; ?>";
    const asunto = "Re: " + "<?php echo $doc['asunto']; ?>";
    const destinatario = "<?php echo $doc['remitente_email']; ?>";
    const nombre = "<?php echo $doc['remitente_nombre']; ?>";
    
    // Podemos usar localStorage para pasar estos datos al formulario de nuevo_documento
    localStorage.setItem('reply_data', JSON.stringify({
        id_padre: idPadre,
        asunto: asunto,
        email: destinatario,
        nombre: nombre
    }));
    
    window.location.href = 'nuevo_documento.php';
}
</script>