<?php
// admin/api/documentos/obtener_historial.php
require_once __DIR__ . '/../../../config.php';
header('Content-Type: application/json');

$doc_id = $_GET['documento_id'] ?? 0;

if (!$doc_id) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-05:00'"
    ]);

    // 1. ENCONTRAR EL DOCUMENTO RAÍZ (El origen de todo el trámite)
    // Subimos en la jerarquía hasta encontrar el que no tiene padre
    $current_id = $doc_id;
    $ids_en_cadena = [$doc_id];

    while (true) {
        $stmt = $pdo->prepare("SELECT documento_padre_id FROM documentos WHERE id = ?");
        $stmt->execute([$current_id]);
        $padre_id = $stmt->fetchColumn();

        if ($padre_id) {
            $current_id = $padre_id;
            $ids_en_cadena[] = $padre_id;
        } else {
            $root_id = $current_id;
            break;
        }
    }

    // 2. ENCONTRAR TODOS LOS DESCENDIENTES (Opcional pero recomendado)
    // Para simplificar, buscaremos todos los docs que tengan como ancestro al root_id o pertenezcan a la cadena
    // En sistemas complejos se usa una búsqueda recursiva, aquí traeremos los directos
    
    // 3. CONSULTAR MOVIMIENTOS DE TODA LA FAMILIA DE DOCUMENTOS
    // Buscamos movimientos de cualquier documento que esté vinculado al ID raíz o sus hijos
    $sql = "
        SELECT m.*, 
               CONCAT(u.nombre, ' ', u.apellido) as remitente_nombre,
               d.codigo as doc_codigo,
               d.asunto as doc_asunto
        FROM movimientos m
        INNER JOIN documentos d ON m.documento_id = d.id
        INNER JOIN usuarios u ON m.remitente_id = u.id
        WHERE d.id = :root OR d.documento_padre_id = :root OR d.id IN (" . implode(',', $ids_en_cadena) . ")
        ORDER BY m.fecha_envio ASC
    ";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':root' => $root_id]);
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $historial]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}