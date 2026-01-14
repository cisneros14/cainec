<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El ID es obligatorio']);
    exit;
}

$id = intval($data['id']);

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar existencia
    $stmt = $pdo->prepare("SELECT id, pregunta FROM faqs WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $faq = $stmt->fetch();

    if (!$faq) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'La FAQ no existe']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = :id");
    $stmt->execute(['id' => $id]);

    echo json_encode(['success' => true, 'message' => 'FAQ eliminada exitosamente', 'data' => ['id' => $id, 'pregunta' => $faq['pregunta']]]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la FAQ', 'error' => $e->getMessage()]);
}
