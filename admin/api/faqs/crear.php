<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data['pregunta']) || empty($data['respuesta'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Pregunta y respuesta son obligatorias']);
    exit;
}

$pregunta = trim($data['pregunta']);
$respuesta = trim($data['respuesta']);
$estado = isset($data['estado']) && in_array($data['estado'], ['activo','inactivo']) ? $data['estado'] : 'activo';

if (strlen($pregunta) < 3) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La pregunta es demasiado corta']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: evitar duplicados exactos por pregunta
    $stmt = $pdo->prepare("SELECT id FROM faqs WHERE pregunta = :pregunta");
    $stmt->execute(['pregunta' => $pregunta]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Ya existe una FAQ con esa pregunta']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO faqs (pregunta, respuesta, estado, creado_en, actualizado_en) VALUES (:pregunta, :respuesta, :estado, NOW(), NOW())");
    $stmt->execute(['pregunta' => $pregunta, 'respuesta' => $respuesta, 'estado' => $estado]);

    $id = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'message' => 'FAQ creada exitosamente', 'data' => ['id' => $id, 'pregunta' => $pregunta, 'respuesta' => $respuesta, 'estado' => $estado]]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear la FAQ', 'error' => $e->getMessage()]);
}
