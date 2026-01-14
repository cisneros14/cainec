<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Obtener datos del POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar campos obligatorios
if (empty($data['id']) || empty($data['nombre']) || empty($data['testimonio'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El ID, nombre y testimonio son obligatorios'
    ]);
    exit;
}

$id = intval($data['id']);
$nombre = trim($data['nombre']);
$testimonio = trim($data['testimonio']);
$cargo = isset($data['cargo']) ? trim($data['cargo']) : null;
$foto_url = isset($data['foto_url']) ? trim($data['foto_url']) : null;

// Validar longitud de campos
if (strlen($nombre) < 2) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre debe tener al menos 2 caracteres'
    ]);
    exit;
}

if (strlen($nombre) > 150) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre no puede exceder 150 caracteres'
    ]);
    exit;
}

if ($cargo && strlen($cargo) > 150) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El cargo no puede exceder 150 caracteres'
    ]);
    exit;
}

if (strlen($testimonio) < 10) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El testimonio debe tener al menos 10 caracteres'
    ]);
    exit;
}

if ($foto_url && strlen($foto_url) > 255) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'La URL de la foto no puede exceder 255 caracteres'
    ]);
    exit;
}

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que el testimonio existe
    $stmt = $pdo->prepare("SELECT id FROM testimonios WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'El testimonio no existe'
        ]);
        exit;
    }
    
    // Actualizar el testimonio
    $stmt = $pdo->prepare("
        UPDATE testimonios 
        SET nombre = :nombre, cargo = :cargo, testimonio = :testimonio, foto_url = :foto_url
        WHERE id = :id
    ");
    
    $stmt->execute([
        'nombre' => $nombre,
        'cargo' => $cargo,
        'testimonio' => $testimonio,
        'foto_url' => $foto_url,
        'id' => $id
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Testimonio actualizado exitosamente',
        'data' => [
            'id' => $id,
            'nombre' => $nombre,
            'cargo' => $cargo,
            'testimonio' => $testimonio,
            'foto_url' => $foto_url
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar el testimonio',
        'error' => $e->getMessage()
    ]);
}
