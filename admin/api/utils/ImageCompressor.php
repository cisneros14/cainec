<?php

class ImageCompressor {
    
    /**
     * Comprime una imagen si excede el tamaño máximo permitido.
     * 
     * @param string $sourcePath Ruta del archivo de origen
     * @param string $destinationPath Ruta donde se guardará la imagen (puede ser la misma)
     * @param int $maxSizeMB Tamaño máximo en Megabytes (default 3MB)
     * @param int $quality Calidad inicial de compresión (0-100)
     * @return bool True si se procesó correctamente, False si hubo error
     */
    public static function compress($sourcePath, $destinationPath, $maxSizeMB = 3, $quality = 80) {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $fileSize = filesize($sourcePath);
        $maxSizeBytes = $maxSizeMB * 1024 * 1024;

        // Si el archivo ya es menor al tamaño máximo, solo mover/copiar si es necesario
        if ($fileSize <= $maxSizeBytes) {
            if ($sourcePath !== $destinationPath) {
                return copy($sourcePath, $destinationPath);
            }
            return true;
        }

        // Obtener información de la imagen
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }

        $mime = $imageInfo['mime'];
        $image = null;

        // Crear recurso de imagen según el tipo
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                // Convertir a true color para poder comprimir mejor si es necesario
                imagepalettetotruecolor($image);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                // Tipo no soportado para compresión, intentar copiar tal cual
                if ($sourcePath !== $destinationPath) {
                    return copy($sourcePath, $destinationPath);
                }
                return true;
        }

        if (!$image) {
            return false;
        }

        // Intentar comprimir reduciendo calidad
        // Para PNG la calidad es 0-9 (compresión), para JPEG/WEBP es 0-100 (calidad)
        // Aquí normalizamos a lógica de "calidad" para JPEG/WEBP. PNG se maneja aparte.
        
        $saved = false;
        
        // Si es muy grande, quizás redimensionar primero? 
        // Por ahora solo compresión de calidad como pidió el usuario, 
        // pero si es gigante en pixeles, bajar calidad podría no bastar.
        // Vamos a implementar un loop simple de reducción de calidad/tamaño.
        
        // Guardar inicialmente
        $saved = self::saveImage($image, $destinationPath, $mime, $quality);
        
        // Verificar tamaño
        clearstatcache();
        while (filesize($destinationPath) > $maxSizeBytes && $quality > 10) {
            $quality -= 10;
            $saved = self::saveImage($image, $destinationPath, $mime, $quality);
            clearstatcache();
        }
        
        // Si aún es muy grande, redimensionar
        if (filesize($destinationPath) > $maxSizeBytes) {
            $width = imagesx($image);
            $height = imagesy($image);
            $ratio = $maxSizeBytes / filesize($destinationPath);
            // Raíz cuadrada para reducir dimensiones proporcionalmente al área
            $newWidth = $width * sqrt($ratio) * 0.9; // 0.9 factor de seguridad
            $newHeight = $height * sqrt($ratio) * 0.9;
            
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preservar transparencia para PNG/WEBP
            if ($mime == 'image/png' || $mime == 'image/webp') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
            
            // Guardar redimensionado
            self::saveImage($image, $destinationPath, $mime, $quality);
        }

        imagedestroy($image);
        return true;
    }

    private static function saveImage($image, $path, $mime, $quality) {
        switch ($mime) {
            case 'image/jpeg':
                return imagejpeg($image, $path, $quality);
            case 'image/png':
                // PNG quality: 0 (no compression) to 9. 
                // Map 0-100 quality to 9-0 compression (approx)
                $compression = round(9 * (1 - ($quality / 100)));
                if ($compression < 0) $compression = 0;
                if ($compression > 9) $compression = 9;
                return imagepng($image, $path, $compression);
            case 'image/webp':
                return imagewebp($image, $path, $quality);
            default:
                return false;
        }
    }
}
?>
