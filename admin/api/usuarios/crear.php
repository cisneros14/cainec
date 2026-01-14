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
$cargo = trim($_POST['cargo'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$licencia = trim($_POST['licencia'] ?? '');
$telefono_contacto = trim($_POST['telefono_contacto'] ?? '');
$telefono_contacto2 = trim($_POST['telefono_contacto2'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$provincia = trim($_POST['provincia'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$pagina_web = trim($_POST['pagina_web'] ?? '');
$facebook = trim($_POST['facebook'] ?? '');
$instagram = trim($_POST['instagram'] ?? '');
$linkedin = trim($_POST['linkedin'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$rol = intval($_POST['rol'] ?? 0);
$estado = intval($_POST['estado'] ?? 1);
$directiva = intval($_POST['directiva'] ?? 0);
$img_url = trim($_POST['img_url'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$formacion = trim($_POST['formacion'] ?? '');
$habilidades = trim($_POST['habilidades'] ?? '');
$certificaciones = trim($_POST['certificaciones'] ?? '');

// Validaciones
$errores = [];

if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 20) {
    $errores[] = 'El nombre es obligatorio y debe tener entre 2 y 20 caracteres';
}

if (empty($apellido) || strlen($apellido) < 2 || strlen($apellido) > 20) {
    $errores[] = 'El apellido es obligatorio y debe tener entre 2 y 20 caracteres';
}

if (empty($cedula_ruc) || strlen($cedula_ruc) > 15) {
    $errores[] = 'La cédula/RUC es obligatoria y debe tener máximo 15 caracteres';
}

if (empty($usuario) || strlen($usuario) < 3 || strlen($usuario) > 64) {
    $errores[] = 'El usuario es obligatorio y debe tener entre 3 y 64 caracteres';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 64) {
    $errores[] = 'El email es obligatorio y debe ser válido (máximo 64 caracteres)';
}

if (empty($password) || strlen($password) < 6) {
    $errores[] = 'La contraseña es obligatoria y debe tener al menos 6 caracteres';
}

if (!empty($errores)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errores)], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El usuario ya existe'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El email ya está registrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Hash de la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
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
        $cargo,
        $empresa,
        $licencia,
        $telefono_contacto,
        $telefono_contacto2,
        $direccion,
        $provincia,
        $ciudad,
        $pagina_web,
        $facebook,
        $instagram,
        $linkedin,
        $usuario,
        $email,
        $password_hash,
        $rol,
        $estado,
        $directiva,
        $img_url,
        $descripcion,
        $formacion,
        $habilidades,
        $certificaciones
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Usuario creado exitosamente',
        'id' => $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear usuario: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
