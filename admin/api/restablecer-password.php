<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$code = trim($_POST['code'] ?? '');
$email = trim($_POST['email'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// Validaciones
if (empty($code) || empty($email) || empty($new_password) || empty($confirm_password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($new_password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (strlen($new_password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Buscar usuario con el código válido
    $stmt = $pdo->prepare("
        SELECT id, nombre, apellido 
        FROM usuarios 
        WHERE email = ? 
        AND password_reset_code = ? 
        AND password_reset_expires > NOW()
        AND estado = 1
        LIMIT 1
    ");
    $stmt->execute([$email, $code]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Código inválido o expirado. Solicita un nuevo código de recuperación'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Actualizar contraseña y limpiar código de recuperación
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        UPDATE usuarios 
        SET password_hash = ?,
            password_reset_code = NULL,
            password_reset_expires = NULL
        WHERE id = ?
    ");
    $stmt->execute([$password_hash, $usuario['id']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Tu contraseña ha sido actualizada exitosamente. Ya puedes iniciar sesión'
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar la contraseña: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
