<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Obtener datos del POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar que se recibió el ID y el nombre
if (empty($data['id']) || empty($data['nombre'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El ID y el nombre son obligatorios'
    ]);
    exit;
}

$id = intval($data['id']);
$nombre = trim($data['nombre']);

// Validar longitud del nombre
if (strlen($nombre) < 2) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre debe tener al menos 2 caracteres'
    ]);
    exit;
}

if (strlen($nombre) > 255) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre no puede exceder 255 caracteres'
    ]);
    exit;
}

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que la categoría existe
    $stmt = $pdo->prepare("SELECT id FROM categoria_blog WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'La categoría no existe'
        ]);
        exit;
    }
    
    // Verificar si ya existe otra categoría con el mismo nombre
    $stmt = $pdo->prepare("SELECT id FROM categoria_blog WHERE nombre = :nombre AND id != :id");
    $stmt->execute(['nombre' => $nombre, 'id' => $id]);
    
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe otra categoría con ese nombre'
        ]);
        exit;
    }
    
    // Actualizar la categoría
    $stmt = $pdo->prepare("
        UPDATE categoria_blog 
        SET nombre = :nombre, updated_at = NOW() 
        WHERE id = :id
    ");
    
    $stmt->execute([
        'nombre' => $nombre,
        'id' => $id
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Categoría actualizada exitosamente',
        'data' => [
            'id' => $id,
            'nombre' => $nombre
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar la categoría',
        'error' => $e->getMessage()
    ]);
}
