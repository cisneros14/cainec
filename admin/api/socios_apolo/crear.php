<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['nombre']) || empty($data['cargo'])) {
        echo json_encode(['success' => false, 'message' => 'Nombre y cargo son obligatorios']);
        exit;
    }
    
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("
        INSERT INTO socios_apolo (nombre, cargo, imagen, anio, logros) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $logros = isset($data['logros']) ? json_encode($data['logros']) : json_encode([]);
    
    $stmt->execute([
        $data['nombre'],
        $data['cargo'],
        $data['imagen'] ?? null,
        $data['anio'] ?? date('Y'),
        $logros
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Socio creado exitosamente',
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear el socio',
        'error' => $e->getMessage()
    ]);
}
?>
