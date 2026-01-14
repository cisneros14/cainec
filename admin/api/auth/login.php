<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config.php';

// Crear conexión PDO
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$pdo = new PDO($dsn, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$remember = isset($_POST['rememberme']);

// Validaciones básicas
if (empty($username) || empty($password)) {
    echo json_encode([
        'success' => false,
        'type' => 'error',
        'message' => 'Por favor, ingrese usuario y contraseña'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Buscar usuario por username o email
    $stmt = $pdo->prepare("
        SELECT id, nombre, apellido, usuario, email, password_hash, rol, estado, img_url
        FROM usuarios 
        WHERE (usuario = ? OR email = ?)
        LIMIT 1
    ");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    // Verificar si existe el usuario
    if (!$user) {
        echo json_encode([
            'success' => false,
            'type' => 'error',
            'message' => 'Usuario o contraseña incorrectos'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Verificar si el usuario está activo
    if ($user['estado'] != 1) {
        echo json_encode([
            'success' => false,
            'type' => 'warning',
            'message' => 'Tu cuenta está inactiva. Contacta al administrador.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Verificar contraseña
    if (!password_verify($password, $user['password_hash'])) {
        echo json_encode([
            'success' => false,
            'type' => 'error',
            'message' => 'Usuario o contraseña incorrectos'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Login exitoso - Crear sesión
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nombre'] = $user['nombre'];
    $_SESSION['user_apellido'] = $user['apellido'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_rol'] = $user['rol'];
    $_SESSION['user_img'] = $user['img_url'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
    
    // Si marcó "recordarme", extender la sesión
    if ($remember) {
        // Sesión de 30 días
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);
        session_set_cookie_params(30 * 24 * 60 * 60);
    }
    
    // Registrar último acceso (opcional)
    // $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?")->execute([$user['id']]);
    
    echo json_encode([
        'success' => true,
        'type' => 'success',
        'message' => '¡Bienvenido/a ' . htmlspecialchars($user['nombre']) . '!',
        'redirect' => 'admin/entradas.php',
        'user' => [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'type' => 'error',
        'message' => 'Error del servidor. Inténtalo nuevamente.'
    ], JSON_UNESCAPED_UNICODE);
}
