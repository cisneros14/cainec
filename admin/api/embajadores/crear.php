<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../../config.php';

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos no válidos']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO embajadores (nombre, apellido, categoria, descripcion, url_img, telefono, correo, orden) 
            VALUES (:nombre, :apellido, :categoria, :descripcion, :url_img, :telefono, :correo, :orden)";
            
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':nombre' => $data['nombre'],
        ':apellido' => $data['apellido'],
        ':categoria' => $data['categoria'] ?? null,
        ':descripcion' => $data['descripcion'] ?? null,
        ':url_img' => $data['url_img'] ?? null,
        ':telefono' => $data['telefono'] ?? null,
        ':correo' => $data['correo'],
        ':orden' => $data['orden'] ?? 0
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Embajador creado exitosamente',
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear el embajador',
        'error' => $e->getMessage()
    ]);
}
?>
