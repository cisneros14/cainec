<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

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

// Obtener datos del POST
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$cedula_ruc = trim($_POST['cedula_ruc'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono_contacto = trim($_POST['telefono_contacto'] ?? '');
$password = trim($_POST['password'] ?? '');
$password_confirm = trim($_POST['password_confirm'] ?? '');
$provincia = trim($_POST['provincia'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');

// Validaciones
$errores = [];

if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 20) {
    $errores[] = 'El nombre es obligatorio y debe tener entre 2 y 20 caracteres';
}

if (empty($apellido) || strlen($apellido) < 2 || strlen($apellido) > 20) {
    $errores[] = 'El apellido es obligatorio y debe tener entre 2 y 20 caracteres';
}

if (empty($cedula_ruc) || strlen($cedula_ruc) > 15) {
    $errores[] = 'La cédula es obligatoria y debe tener máximo 15 caracteres';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 64) {
    $errores[] = 'El email es obligatorio y debe ser válido';
}

if (empty($telefono_contacto)) {
    $errores[] = 'El teléfono de contacto es obligatorio';
}

if (empty($password) || strlen($password) < 6) {
    $errores[] = 'La contraseña debe tener al menos 6 caracteres';
}

if ($password !== $password_confirm) {
    $errores[] = 'Las contraseñas no coinciden';
}

if (empty($provincia)) {
    $errores[] = 'La provincia es obligatoria';
}

if (empty($ciudad)) {
    $errores[] = 'La ciudad es obligatoria';
}

if (!empty($errores)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errores)], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El email ya está registrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Verificar si la cédula ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE cedula_ruc = ?");
    $stmt->execute([$cedula_ruc]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'La cédula ya está registrada'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Generar usuario a partir del email (parte antes del @)
    $usuario = explode('@', $email)[0];
    $usuario_base = $usuario;
    $counter = 1;
    
    // Asegurar que el usuario sea único
    while (true) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if (!$stmt->fetch()) {
            break;
        }
        $usuario = $usuario_base . $counter;
        $counter++;
    }
    
    // Hash de la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario con rol=2 (usuario registrado) y estado=0 (inactivo/pendiente)
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (
            nombre, apellido, cedula_ruc, cargo, empresa, licencia,
            telefono_contacto, telefono_contacto2, direccion, provincia, ciudad, pagina_web,
            facebook, instagram, linkedin,
            usuario, email, password_hash, rol, estado, directiva, img_url,
            descripcion, formacion, habilidades, certificaciones
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?
        )
    ");
    
    $stmt->execute([
        $nombre, 
        $apellido, 
        $cedula_ruc, 
        '', // cargo
        '', // empresa
        '', // licencia
        $telefono_contacto, 
        '', // telefono_contacto2
        '', // direccion
        $provincia, 
        $ciudad, 
        '', // pagina_web
        '', // facebook
        '', // instagram
        '', // linkedin
        $usuario, 
        $email, 
        $password_hash, 
        2, // rol = 2 (usuario registrado)
        0, // estado = 0 (inactivo/pendiente aprobación)
        0, // directiva
        null, // img_url
        null, // descripcion
        null, // formacion
        null, // habilidades
        null  // certificaciones
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Registro exitoso. Tu cuenta está pendiente de aprobación por un administrador.'
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar usuario. Por favor, intenta más tarde.'
    ], JSON_UNESCAPED_UNICODE);
}
