<?php
// ver_documento.php (En la raíz)
require_once '../config.php'; // Ajusta la ruta si es necesario

$doc = null;
$adjuntos = [];

if (isset($_GET['t'])) {
    try {
        // 1. DECODIFICAR ID
        $movimiento_id = base64_decode($_GET['t']);

        // 2. CONEXIÓN CON CHARSET UTF8MB4 (Evita caracteres raros)
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);

        // 3. MARCAR COMO LEÍDO (Tracking)
        $sqlTrack = "UPDATE movimientos SET fecha_lectura = NOW(), accion = 'LEIDO' 
                     WHERE id = ? AND fecha_lectura IS NULL";
        $pdo->prepare($sqlTrack)->execute([$movimiento_id]);

        // 4. OBTENER EL DOCUMENTO
        $stmt = $pdo->prepare("
            SELECT d.*, m.destinatario_email, td.nombre as tipo_nombre
            FROM movimientos m 
            JOIN documentos d ON m.documento_id = d.id 
            JOIN tipos_documento td ON d.tipo_id = td.id
            WHERE m.id = ?
        ");
        $stmt->execute([$movimiento_id]);
        $doc = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doc) {
            // 5. OBTENER ADJUNTOS
            $stmtAdj = $pdo->prepare("SELECT * FROM adjuntos WHERE documento_id = ?");
            $stmtAdj->execute([$doc['id']]);
            $adjuntos = $stmtAdj->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (Exception $e) {
        // En producción es mejor loguear el error y mostrar un mensaje genérico
        error_log($e->getMessage());
    }
}

if (!$doc) {
    die("Error: El documento no existe o el enlace ha expirado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Documento - <?php echo htmlspecialchars($doc['codigo']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10 px-4">

    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white shadow-2xl rounded-sm border border-gray-200 overflow-hidden mb-6">
            
            <div class="p-8 border-b-2 border-gray-100 bg-gray-50 flex justify-between items-start">
                <div>
                    <h2 class="text-blue-900 font-black text-2xl uppercase tracking-tighter">
                        <?php echo htmlspecialchars($doc['tipo_nombre']); ?>
                    </h2>
                    <p class="font-mono text-gray-500 text-lg"><?php echo htmlspecialchars($doc['codigo']); ?></p>
                </div>
                <div class="text-right text-sm text-gray-400">
                    <p>Fecha de Emisión:</p>
                    <p class="font-bold text-gray-700"><?php echo date('d/m/Y H:i', strtotime($doc['fecha_creacion'])); ?></p>
                </div>
            </div>

            <div class="px-8 py-4 bg-white border-b border-gray-50">
                <p class="text-sm"><span class="font-bold text-gray-400 uppercase text-xs mr-2">Para:</span> <?php echo htmlspecialchars($doc['destinatario_email']); ?></p>
                <p class="text-sm mt-1"><span class="font-bold text-gray-400 uppercase text-xs mr-2">Asunto:</span> <?php echo htmlspecialchars($doc['asunto']); ?></p>
            </div>

            <div class="p-10 min-h-[400px]">
                <div class="prose max-w-none text-gray-800 leading-relaxed text-lg">
                    <?php echo nl2br(htmlspecialchars($doc['cuerpo'])); ?>
                </div>
            </div>

            <div class="p-10 pt-0">
                <div class="mt-20 w-64 border-t border-gray-300">
                    <p class="text-xs text-gray-400 mt-2 uppercase">Firmado Digitalmente</p>
                </div>
            </div>
        </div>

        <?php if (count($adjuntos) > 0): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Archivos Adjuntos
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($adjuntos as $adj): ?>
                    <a href="../<?php echo htmlspecialchars($adj['ruta_archivo']); ?>" 
                       target="_blank" 
                       class="flex items-center p-4 border rounded-xl hover:bg-blue-50 transition-all group">
                        <div class="p-2 bg-gray-100 rounded-lg group-hover:bg-blue-100 transition">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-700 truncate max-w-[200px]"><?php echo htmlspecialchars($adj['nombre_original']); ?></p>
                            <p class="text-xs text-blue-500 font-medium">Click para descargar</p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-8 text-center text-gray-400 text-xs">
            Este documento es una comunicación oficial de CAINEC. <br>
            © 2026 Sistema de Gestión Documental.
        </div>
    </div>

</body>
</html>