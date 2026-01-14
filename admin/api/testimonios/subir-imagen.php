<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Directorio donde se guardarán las imágenes (3 niveles arriba: testimonios -> api -> admin -> cainec)
$uploadDir = __DIR__ . '/../../../assets/images/testimonial/';
$uploadDirRelative = 'assets/images/testimonial/';

// Normalizar la ruta
$uploadDir = realpath(__DIR__ . '/../../../') . '/assets/images/testimonial/';

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    chmod($uploadDir, 0777);
}

// Validar que se subió un archivo
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'No se subió ninguna imagen'
    ]);
    exit;
}

$file = $_FILES['imagen'];

// Validar errores de subida
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error al subir el archivo'
    ]);
    exit;
}

// Validar tamaño (máximo 20MB para permitir subida y luego comprimir)
$maxSize = 20 * 1024 * 1024; 
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El archivo es demasiado grande (máximo 20MB)'
    ]);
    exit;
}

// Validar tipo de archivo
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Formato de imagen no válido. Solo se permiten JPG, PNG y WEBP'
    ]);
    exit;
}

// Obtener extensión del archivo
$extension = '';
switch ($mimeType) {
    case 'image/jpeg':
    case 'image/jpg':
        $extension = 'jpg';
        break;
    case 'image/png':
        $extension = 'png';
        break;
    case 'image/webp':
        $extension = 'webp';
        break;
}

// Generar nombre único para el archivo
$fileName = 'testimonio_' . uniqid() . '_' . time() . '.' . $extension;
$uploadPath = $uploadDir . $fileName;

// Verificar permisos del directorio
if (!is_writable($uploadDir)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'El directorio de destino no tiene permisos de escritura',
        'debug' => [
            'directory' => $uploadDir,
            'directory_realpath' => realpath($uploadDir),
            'writable' => is_writable($uploadDir),
            'exists' => file_exists($uploadDir),
            'is_dir' => is_dir($uploadDir),
            'permissions' => file_exists($uploadDir) ? substr(sprintf('%o', fileperms($uploadDir)), -4) : 'N/A',
            'parent_dir' => dirname($uploadDir),
            'parent_writable' => is_writable(dirname($uploadDir))
        ]
    ]);
    exit;
}

// Mover archivo al directorio de destino
require_once __DIR__ . '/../utils/ImageCompressor.php';

// Intentar comprimir/guardar usando la utilidad
if (ImageCompressor::compress($file['tmp_name'], $uploadPath, 3)) {
    // Retornar la URL relativa
    $fileUrl = $uploadDirRelative . $fileName;
    
    echo json_encode([
        'success' => true,
        'message' => 'Imagen subida exitosamente',
        'data' => [
            'url' => $fileUrl,
            'filename' => $fileName
        ]
    ]);
} else {
    http_response_code(500);
    
    // Obtener información detallada del error
    $errorInfo = error_get_last();
    $debugInfo = [
        'tmp_name' => $file['tmp_name'],
        'tmp_exists' => file_exists($file['tmp_name']),
        'upload_path' => $uploadPath,
        'upload_dir' => $uploadDir,
        'upload_dir_exists' => file_exists($uploadDir),
        'upload_dir_writable' => is_writable($uploadDir),
        'upload_dir_permissions' => file_exists($uploadDir) ? substr(sprintf('%o', fileperms($uploadDir)), -4) : 'N/A',
        'php_error' => $errorInfo ? $errorInfo['message'] : 'No hay error de PHP registrado'
    ];
    
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el archivo en el servidor',
        'debug' => $debugInfo
    ]);
}
