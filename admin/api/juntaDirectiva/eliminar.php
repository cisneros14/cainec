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

    // Verificar que el miembro existe y obtener la URL de la imagen
    $stmt = $pdo->prepare("SELECT id, nombre, apellido, url_img FROM junta_directiva WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $miembro = $stmt->fetch();

    if (!$miembro) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'El miembro no existe'
        ]);
        exit;
    }

    // Eliminar la imagen física si existe
    if (!empty($miembro['url_img'])) {
        $imagePath = __DIR__ . '/../../../' . $miembro['url_img'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Eliminar el miembro
    $stmt = $pdo->prepare("DELETE FROM junta_directiva WHERE id = :id");
    $stmt->execute(['id' => $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Miembro eliminado exitosamente',
        'data' => [
            'id' => $id,
            'nombre' => $miembro['nombre'] . ' ' . $miembro['apellido']
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar el miembro',
        'error' => $e->getMessage()
    ]);
}
