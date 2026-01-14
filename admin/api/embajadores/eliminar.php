<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../../config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID faltante']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener la imagen antes de eliminar
    $stmt = $pdo->prepare("SELECT url_img FROM embajadores WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $embajador = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eliminar archivo de imagen si existe
    if ($embajador && !empty($embajador['url_img'])) {
        $imagePath = __DIR__ . '/../../../' . $embajador['url_img'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM embajadores WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Embajador eliminado exitosamente'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar el embajador',
        'error' => $e->getMessage()
    ]);
}
?>