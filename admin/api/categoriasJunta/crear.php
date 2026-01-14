<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Obtener datos del POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar campo obligatorio
if (empty($data['nombre'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre de la categoría es obligatorio'
    ]);
    exit;
}

$nombre = trim($data['nombre']);

// Validar longitud
if (strlen($nombre) < 2 || strlen($nombre) > 200) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre debe tener entre 2 y 200 caracteres'
    ]);
    exit;
}

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si ya existe una categoría con ese nombre
    $stmt = $pdo->prepare("SELECT id FROM categorias_junta WHERE nombre = :nombre");
    $stmt->execute(['nombre' => $nombre]);

    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una categoría con ese nombre'
        ]);
        exit;
    }

    // Insertar la nueva categoría
    $stmt = $pdo->prepare("INSERT INTO categorias_junta (nombre) VALUES (:nombre)");
    $stmt->execute(['nombre' => $nombre]);

    $id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Categoría creada exitosamente',
        'data' => [
            'id' => $id,
            'nombre' => $nombre
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear la categoría',
        'error' => $e->getMessage()
    ]);
}
