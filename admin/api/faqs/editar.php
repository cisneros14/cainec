<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data['id']) || empty($data['pregunta']) || empty($data['respuesta'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID, pregunta y respuesta son obligatorios']);
    exit;
}

$id = intval($data['id']);
$pregunta = trim($data['pregunta']);
$respuesta = trim($data['respuesta']);
$estado = isset($data['estado']) && in_array($data['estado'], ['activo','inactivo']) ? $data['estado'] : 'activo';

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar existencia
    $stmt = $pdo->prepare("SELECT id FROM faqs WHERE id = :id");
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'La FAQ no existe']);
        exit;
    }

    // Opcional: verificar duplicado en otra fila
    $stmt = $pdo->prepare("SELECT id FROM faqs WHERE pregunta = :pregunta AND id != :id");
    $stmt->execute(['pregunta' => $pregunta, 'id' => $id]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Otra FAQ con la misma pregunta ya existe']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE faqs SET pregunta = :pregunta, respuesta = :respuesta, estado = :estado, actualizado_en = NOW() WHERE id = :id");
    $stmt->execute(['pregunta' => $pregunta, 'respuesta' => $respuesta, 'estado' => $estado, 'id' => $id]);

    echo json_encode(['success' => true, 'message' => 'FAQ actualizada exitosamente', 'data' => ['id' => $id, 'pregunta' => $pregunta, 'respuesta' => $respuesta, 'estado' => $estado]]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la FAQ', 'error' => $e->getMessage()]);
}
