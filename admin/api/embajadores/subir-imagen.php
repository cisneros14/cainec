<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Definir directorio de subida
$uploadDir = __DIR__ . '/../../../assets/images/embajadores/';
$uploadUrl = 'assets/images/embajadores/';

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES['imagen'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se ha enviado ninguna imagen']);
    exit;
}

$file = $_FILES['imagen'];
$fileName = uniqid() . '_' . basename($file['name']);
$targetPath = $uploadDir . $fileName;

// Validar tipo de archivo
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido']);
    exit;
}

require_once __DIR__ . '/../utils/ImageCompressor.php';

if (ImageCompressor::compress($file['tmp_name'], $targetPath, 3)) {
    echo json_encode([
        'success' => true,
        'message' => 'Imagen subida exitosamente',
        'data' => [
            'url' => $uploadUrl . $fileName
        ]
    ]);
} else {
    // Fallback: intentar mover el archivo sin comprimir si la compresión falla
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode([
            'success' => true,
            'message' => 'Imagen subida exitosamente (sin compresión)',
            'data' => [
                'url' => $uploadUrl . $fileName
            ]
        ]);
    } else {
        http_response_code(500);
        $error = error_get_last();
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar la imagen: ' . ($error['message'] ?? 'Error desconocido'),
            'debug_path' => $targetPath
        ]);
    }
}
?>