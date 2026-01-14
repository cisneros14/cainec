<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Obtener datos del cuerpo de la petición
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['orden']) || !is_array($input['orden'])) {
        throw new Exception('Datos inválidos. Se requiere un array de IDs en el campo "orden".');
    }

    $orden = $input['orden'];

    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Iniciar transacción
    $pdo->beginTransaction();

    // Preparar sentencia update
    $stmt = $pdo->prepare("UPDATE junta_directiva SET orden = :orden WHERE id = :id");

    // Actualizar el orden de cada miembro
    foreach ($orden as $index => $id) {
        $stmt->execute([
            ':orden' => $index + 1, // 1-based index
            ':id' => $id
        ]);
    }

    // Confirmar transacción
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Orden actualizado correctamente'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar el orden',
        'error' => $e->getMessage()
    ]);
}
