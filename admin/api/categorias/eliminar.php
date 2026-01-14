<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Obtener datos del POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar que se recibió el ID
if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El ID es obligatorio'
    ]);
    exit;
}

$id = intval($data['id']);

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que la categoría existe
    $stmt = $pdo->prepare("SELECT id, nombre FROM categoria_blog WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $categoria = $stmt->fetch();
    
    if (!$categoria) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'La categoría no existe'
        ]);
        exit;
    }
    
    // Eliminar la categoría
    $stmt = $pdo->prepare("DELETE FROM categoria_blog WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Categoría eliminada exitosamente',
        'data' => [
            'id' => $id,
            'nombre' => $categoria['nombre']
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar la categoría',
        'error' => $e->getMessage()
    ]);
}
