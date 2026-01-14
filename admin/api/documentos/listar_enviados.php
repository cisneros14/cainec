<?php
// admin/api/documentos/listar_enviados.php
header('Content-Type: application/json; charset=utf-8');

$rootPath = __DIR__ . '/../../../';
require_once $rootPath . 'config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$usuario_id = $_SESSION['user_id'] ?? 0;

if ($usuario_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Sesión no iniciada']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-05:00'"
    ]);

    // Consultamos lo que YO envié
    $sql = "
        SELECT 
            m.id as movimiento_id,
            m.fecha_envio,
            m.accion,
            m.destinatario_email, -- Mostramos el destino
            d.codigo,
            d.asunto,
            d.id as documento_id
        FROM movimientos m
        INNER JOIN documentos d ON m.documento_id = d.id
        WHERE m.remitente_id = ? 
        ORDER BY m.fecha_envio DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $documentos]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}