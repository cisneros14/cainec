<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

// Obtener datos JSON del body
$input = json_decode(file_get_contents('php://input'), true);

// Si no hay datos JSON, intentar con POST
if (!$input) {
    $input = $_POST;
}

// Validar ID
if (empty($input['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado'
    ]);
    exit;
}

$id = intval($input['id']);

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener la URL de la portada antes de eliminar
    $stmt = $pdo->prepare("SELECT portada_url FROM blogs WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $blog = $stmt->fetch();
    
    if (!$blog) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'La entrada no existe'
        ]);
        exit;
    }
    
    // Eliminar el registro
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    // Eliminar la imagen física si existe
    if ($blog['portada_url']) {
        $file_path = __DIR__ . '/../../../' . $blog['portada_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Entrada eliminada exitosamente'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar la entrada',
        'error' => $e->getMessage()
    ]);
}
