<?php
// Configuración de errores para API JSON
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Verificar extensión GD
    if (!extension_loaded('gd')) {
        throw new Exception("La extensión GD de PHP no está instalada o habilitada.");
    }

    // Aumentar límite de memoria
    ini_set('memory_limit', '256M');

    // Directorio donde se guardarán las imágenes
    $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/images/juntaDirectiva/';
    $uploadDirRelative = 'assets/images/juntaDirectiva/';

    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception("No se pudo crear el directorio de destino.");
        }
        chmod($uploadDir, 0777);
    }

    // Validar que se subió un archivo
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception("No se subió ninguna imagen", 400);
    }

    $file = $_FILES['imagen'];

    // Validar errores de subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Error al subir el archivo: Código " . $file['error'], 400);
    }

    // Validar tamaño (máximo 10MB)
    $maxSize = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxSize) {
        throw new Exception("El archivo excede el tamaño máximo de 10MB", 400);
    }

    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Formato de imagen no válido. Solo se permiten JPG, PNG y WEBP", 400);
    }

    // Obtener extensión original
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

    // Generar nombre único base
    $baseFileName = 'junta_' . uniqid() . '_' . time();
    $fileName = '';
    $uploadPath = '';
    $optimized = false;

    // Verificar si necesita optimización (> 2MB)
    $needsOptimization = $file['size'] > (2 * 1024 * 1024);

    if ($needsOptimization) {
        try {
            $fileName = $baseFileName . '.webp';
            $uploadPath = $uploadDir . $fileName;

            // Cargar imagen
            $sourceImage = null;
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $sourceImage = @imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'image/png':
                    $sourceImage = @imagecreatefrompng($file['tmp_name']);
                    break;
                case 'image/webp':
                    $sourceImage = @imagecreatefromwebp($file['tmp_name']);
                    break;
            }

            if (!$sourceImage) {
                throw new Exception("No se pudo cargar la imagen para optimización.");
            }

            // Dimensiones
            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            // Redimensionar si es necesario
            $maxWidth = 1200;
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = floor($height * ($maxWidth / $width));

                $newImage = imagecreatetruecolor($newWidth, $newHeight);

                if ($mimeType == 'image/png' || $mimeType == 'image/webp') {
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($sourceImage);
                $sourceImage = $newImage;
            }

            // Guardar WebP
            if (!imagewebp($sourceImage, $uploadPath, 80)) {
                throw new Exception("Fallo al guardar imagen WebP");
            }

            imagedestroy($sourceImage);
            $optimized = true;

        } catch (Throwable $e) {
            // Si falla optimización, log y fallback
            $optimizationError = $e->getMessage();
            error_log("Fallo optimización de imagen: " . $optimizationError);
            if (isset($sourceImage) && $sourceImage)
                imagedestroy($sourceImage);
            $optimized = false;
        }
    }

    // Si no se optimizó (o no era necesario), mover el original
    if (!$optimized) {
        $fileName = $baseFileName . '.' . $extension;
        $uploadPath = $uploadDir . $fileName;

        // Verificar permisos del directorio antes de mover
        if (!is_writable($uploadDir)) {
            throw new Exception('El directorio de destino no tiene permisos de escritura', 500);
        }

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Obtener información detallada del error
            $errorInfo = error_get_last();
            $errorMessage = "Error al mover el archivo subido al destino final.";
            if ($errorInfo && isset($errorInfo['message'])) {
                $errorMessage .= " PHP Error: " . $errorInfo['message'];
            }
            throw new Exception($errorMessage, 500);
        }
    }

    // Respuesta exitosa
    $response = [
        'success' => true,
        'message' => $optimized ? 'Imagen optimizada y subida exitosamente' : 'Imagen subida exitosamente (sin optimización)',
        'data' => [
            'url' => $uploadDirRelative . $fileName,
            'filename' => $fileName
        ],
        'debug_info' => [
            'original_size' => $file['size'],
            'needs_optimization' => $needsOptimization,
            'optimized' => $optimized,
            'optimization_error' => isset($optimizationError) ? $optimizationError : null,
            'memory_limit' => ini_get('memory_limit'),
            'gd_info' => gd_info()
        ]
    ];
    echo json_encode($response);

} catch (Throwable $e) {
    // Capturar cualquier error y devolver JSON válido
    $code = $e->getCode() ?: 500;
    // Asegurar que el código sea un HTTP status válido (algunos códigos de excepción no lo son)
    if ($code < 100 || $code > 599)
        $code = 500;

    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
