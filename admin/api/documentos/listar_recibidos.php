<?php
// admin/api/documentos/listar_recibidos.php

header('Content-Type: application/json; charset=utf-8');

// 1. CARGAR CONFIGURACIÓN
$rootPath = __DIR__ . '/../../../';
if (file_exists($rootPath . 'config.php')) {
    require_once $rootPath . 'config.php';
} else {
    require_once '../../config.php';
}

// 2. SESIÓN
if (session_status() === PHP_SESSION_NONE) session_start();
$usuario_id = $_SESSION['user_id'] ?? 0;

if ($usuario_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Sesión no iniciada']);
    exit;
}

try {
    // 3. CONEXIÓN (CORREGIDA)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-05:00'"
    ]);

    // 4. OBTENER EMAIL DEL USUARIO (Para registros antiguos)
    $stmtUser = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
    $stmtUser->execute([$usuario_id]);
    $mi_email = $stmtUser->fetchColumn();

    // 5. CONSULTA DE BANDEJA DE ENTRADA
    $sql = "
        SELECT 
            m.id as movimiento_id,
            m.fecha_envio,
            m.accion,
            m.fecha_lectura,
            d.codigo,
            d.asunto,
            d.id as documento_id,
            CONCAT(u.nombre, ' ', u.apellido) as remitente_nombre,
            CASE WHEN m.accion = 'LEIDO' THEN 1 ELSE 0 END as leido
        FROM movimientos m
        INNER JOIN documentos d ON m.documento_id = d.id
        INNER JOIN usuarios u ON m.remitente_id = u.id
        WHERE m.destinatario_id = :user_id 
           OR m.destinatario_email = :email
        ORDER BY m.fecha_envio DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $usuario_id,
        ':email'   => $mi_email
    ]);
    
    $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'data' => $documentos
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}