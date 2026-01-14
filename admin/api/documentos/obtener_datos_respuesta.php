<?php
// admin/api/documentos/obtener_datos_respuesta.php
require_once __DIR__ . '/../../../config.php';
header('Content-Type: application/json');

session_start();
$mov_id = $_GET['mov_id'] ?? 0;

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    
    // Traemos datos del documento original y del remitente para responderle
    $sql = "SELECT 
                d.id as documento_id, 
                d.asunto, 
                d.codigo, 
                u.id as remitente_id, 
                CONCAT(u.nombre, ' ', u.apellido) as nombre_completo,
                COALESCE(NULLIF(s.from_email, ''), s.smtp_username, u.email) as email_respuesta
            FROM movimientos m
            JOIN documentos d ON m.documento_id = d.id
            JOIN usuarios u ON m.remitente_id = u.id
            LEFT JOIN smtp_config s ON u.id = s.user_id AND s.is_active = 1
            WHERE m.id = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$mov_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No se encontró el documento original']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}