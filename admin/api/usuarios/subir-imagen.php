<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Validar que se haya enviado un archivo
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'No se ha enviado ningún archivo o hubo un error en la carga'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$archivo = $_FILES['imagen'];

// Validar tipo de archivo
$tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $archivo['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $tiposPermitidos)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Tipo de archivo no permitido. Solo se aceptan JPG, PNG y WEBP'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Validar tamaño (máximo 5MB)
$tamañoMaximo = 5 * 1024 * 1024; // 5MB
if ($archivo['size'] > $tamañoMaximo) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El archivo es demasiado grande. Tamaño máximo: 5MB'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Directorio de destino (3 niveles arriba: usuarios -> api -> admin -> cainec)
    $directorioDestino = __DIR__ . '/../../../assets/images/users/';
    
    // Crear directorio si no existe
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }
    
    // Generar nombre único para el archivo
    $extension = match($mimeType) {
        'image/jpeg', 'image/jpg' => '.jpg',
        'image/png' => '.png',
        'image/webp' => '.webp',
        default => '.jpg'
    };
    
    $nombreArchivo = 'user_' . uniqid() . '_' . time() . $extension;
    $rutaCompleta = $directorioDestino . $nombreArchivo;
    
    // Mover archivo
    if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        throw new Exception('Error al guardar el archivo en el servidor');
    }
    
    // Ruta relativa para guardar en BD
    $rutaRelativa = 'assets/images/users/' . $nombreArchivo;
    
    echo json_encode([
        'success' => true,
        'message' => 'Imagen subida exitosamente',
        'img_url' => $rutaRelativa
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al subir imagen: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
