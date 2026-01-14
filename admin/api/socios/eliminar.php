<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Soft delete (cambiar estado a 0) o hard delete? 
    // El schema tiene columna 'estado', así que usaremos soft delete o hard delete según preferencia.
    // Por simplicidad y consistencia con otros módulos, haremos hard delete por ahora, 
    // o update estado = 0 si se prefiere. 
    // Dado que listar.php filtra por estado=1, haremos soft delete.
    
    $sql = "UPDATE socios SET estado = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $data['id']]);

    echo json_encode(['success' => true, 'message' => 'Socio eliminado exitosamente']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar socio: ' . $e->getMessage()]);
}
?>
