<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de configuración requerido']);
    exit;
}

// Validate required fields
$required = ['user_id', 'smtp_host', 'smtp_username', 'smtp_password', 'from_email'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
        exit;
    }
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If this is set as default, unset others for this user
    if (!empty($data['is_default'])) {
        $stmt = $pdo->prepare("UPDATE smtp_config SET is_default = 0 WHERE user_id = ? AND id != ?");
        $stmt->execute([$data['user_id'], $data['id']]);
    }

    $sql = "UPDATE smtp_config SET 
        user_id = :user_id,
        smtp_host = :smtp_host,
        smtp_port = :smtp_port,
        smtp_username = :smtp_username,
        smtp_password = :smtp_password,
        encryption = :encryption,
        from_name = :from_name,
        from_email = :from_email,
        is_default = :is_default,
        is_active = :is_active
        WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':user_id' => $data['user_id'],
        ':smtp_host' => $data['smtp_host'],
        ':smtp_port' => $data['smtp_port'] ?? 587,
        ':smtp_username' => $data['smtp_username'],
        ':smtp_password' => $data['smtp_password'],
        ':encryption' => $data['encryption'] ?? 'tls',
        ':from_name' => $data['from_name'] ?? null,
        ':from_email' => $data['from_email'],
        ':is_default' => !empty($data['is_default']) ? 1 : 0,
        ':is_active' => isset($data['is_active']) ? ($data['is_active'] ? 1 : 0) : 1,
        ':id' => $data['id']
    ]);

    echo json_encode(['success' => true, 'message' => 'Configuración SMTP actualizada exitosamente']);

} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Error: Ya existe una configuración para este usuario y email']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar configuración: ' . $e->getMessage()]);
    }
}
?>
