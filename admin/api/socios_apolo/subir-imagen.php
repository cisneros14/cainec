<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se ha subido ninguna imagen o hubo un error');
    }

    $file = $_FILES['imagen'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG y WEBP');
    }

    // Crear directorio si no existe
    $uploadDir = __DIR__ . '/../../../assets/images/socios_apolo/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('socio_') . '.' . $extension;
    $targetPath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $relativePath = 'assets/images/socios_apolo/' . $filename;
        echo json_encode([
            'success' => true,
            'message' => 'Imagen subida exitosamente',
            'data' => ['url' => $relativePath]
        ]);
    } else {
        throw new Exception('Error al mover el archivo subido');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
