<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT s.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido, u.email as usuario_email 
            FROM smtp_config s
            LEFT JOIN usuarios u ON s.user_id = u.id
            ORDER BY s.created_at DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $configs]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener configuraciones: ' . $e->getMessage()]);
}
?>
