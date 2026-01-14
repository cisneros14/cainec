<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

// Obtener datos del POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar campos obligatorios
if (empty($data['id']) || empty($data['nombre']) || empty($data['apellido']) || empty($data['categoria']) || empty($data['rol']) || empty($data['correo'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Los campos ID, nombre, apellido, categoría, rol y correo son obligatorios'
    ]);
    exit;
}

$id = intval($data['id']);
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

    // Verificar que el miembro existe y obtener la imagen actual
    $stmt = $pdo->prepare("SELECT id, url_img FROM junta_directiva WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $miembroActual = $stmt->fetch();

    if (!$miembroActual) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'El miembro no existe'
        ]);
        exit;
    }

    // Si se está actualizando la imagen, eliminar la anterior
    if (!empty($url_img) && !empty($miembroActual['url_img']) && $url_img !== $miembroActual['url_img']) {
        $oldImagePath = __DIR__ . '/../../../' . $miembroActual['url_img'];
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    // Actualizar el miembro
    $stmt = $pdo->prepare("
        UPDATE junta_directiva 
        SET nombre = :nombre, apellido = :apellido, categoria = :categoria, rol = :rol, descripcion = :descripcion,
            url_img = :url_img, telefono = :telefono, correo = :correo
        WHERE id = :id
    ");

    $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'categoria' => $categoria,
        'rol' => $rol,
        'descripcion' => $descripcion,
        'url_img' => $url_img,
        'telefono' => $telefono,
        'correo' => $correo,
        'id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Miembro actualizado exitosamente',
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
        'message' => 'Error al actualizar el miembro',
        'error' => $e->getMessage()
    ]);
}
