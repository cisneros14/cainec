<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Obtener datos del POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log para debugging
error_log("Datos recibidos en crear.php: " . print_r($data, true));

// Validar campos obligatorios
if (empty($data['nombre']) || empty($data['apellido']) || empty($data['categoria']) || empty($data['rol']) || empty($data['correo'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Los campos nombre, apellido, categoría, rol y correo son obligatorios',
        'debug' => [
            'nombre' => isset($data['nombre']) ? 'OK' : 'FALTA',
            'apellido' => isset($data['apellido']) ? 'OK' : 'FALTA',
            'categoria' => isset($data['categoria']) ? 'OK' : 'FALTA',
            'rol' => isset($data['rol']) ? 'OK' : 'FALTA',
            'correo' => isset($data['correo']) ? 'OK' : 'FALTA'
        ]
    ]);
    exit;
}


$nombre = trim($data['nombre']);
$apellido = trim($data['apellido']);
$categoria = trim($data['categoria']);
$rol = trim($data['rol']);
$correo = trim($data['correo']);
$descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : null;
$url_img = isset($data['url_img']) && !empty($data['url_img']) ? trim($data['url_img']) : null;
$telefono = isset($data['telefono']) && !empty($data['telefono']) ? trim($data['telefono']) : null;


// Validar longitud de campos
if (strlen($nombre) < 2 || strlen($nombre) > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El nombre debe tener entre 2 y 100 caracteres'
    ]);
    exit;
}

if (strlen($apellido) < 2 || strlen($apellido) > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El apellido debe tener entre 2 y 100 caracteres'
    ]);
    exit;
}

if (strlen($categoria) > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'La categoría no puede exceder 100 caracteres'
    ]);
    exit;
}

if (strlen($rol) > 150) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El rol no puede exceder 150 caracteres'
    ]);
    exit;
}

if (strlen($correo) > 150) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El correo no puede exceder 150 caracteres'
    ]);
    exit;
}

// Validar formato de correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El formato del correo electrónico no es válido'
    ]);
    exit;
}

if ($url_img && strlen($url_img) > 255) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'La URL de la imagen no puede exceder 255 caracteres'
    ]);
    exit;
}

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insertar el nuevo miembro
    $stmt = $pdo->prepare("
        INSERT INTO junta_directiva (nombre, apellido, categoria, rol, descripcion, url_img, telefono, correo) 
        VALUES (:nombre, :apellido, :categoria, :rol, :descripcion, :url_img, :telefono, :correo)
    ");

    $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'categoria' => $categoria,
        'rol' => $rol,
        'descripcion' => $descripcion,
        'url_img' => $url_img,
        'telefono' => $telefono,
        'correo' => $correo
    ]);

    $id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Miembro creado exitosamente',
        'data' => [
            'id' => $id,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'categoria' => $categoria,
            'rol' => $rol,
            'descripcion' => $descripcion,
            'url_img' => $url_img,
            'telefono' => $telefono,
            'correo' => $correo
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear el miembro',
        'error' => $e->getMessage()
    ]);
}
