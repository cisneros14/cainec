<?php
header('Content-Type: application/json');
require_once '../../auth_middleware.php';

// Verificar si es administrador
$user = verificarRol([1]);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos
$id = $_POST['id'] ?? null;
$estado = $_POST['estado'] ?? null;

if (!$id || $estado === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID y estado son requeridos']);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Verificar si el usuario existe
    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id");
    $stmtCheck->execute([':id' => $id]);
    if (!$stmtCheck->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }

    // Actualizar estado
    $sql = "UPDATE usuarios SET estado = :estado WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([':estado' => $estado, ':id' => $id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error inesperado: ' . $e->getMessage()]);
}
?>