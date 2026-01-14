<?php
// Archivo de configuración global para la aplicación
// Define constantes y configuraciones que deben estar disponibles en todo el proyecto.

// Si ya existe una constante URL_APP definida (por ejemplo en un entorno), no la sobreescribimos.
if (!defined('URL_APP')) {
    // Detectar URL base automáticamente si es posible
    // Intentamos construir a partir de variables de servidor. Si fallan, el usuario puede editar este archivo manualmente.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';

    if (!empty($_SERVER['HTTP_HOST'])) {
        // Obtenemos el path hasta la raíz del proyecto (asumiendo que este archivo está en la raíz)
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = rtrim(dirname($scriptName), '\\/');
        $scriptDir = $scriptDir === '/' ? '' : $scriptDir;
        $detected = $protocol . '://' . $_SERVER['HTTP_HOST'] . $scriptDir . '/';
    } else {
        // Valor por defecto local (editar según sea necesario)
        $detected = 'http://localhost/cainec/';
    }

    define('URL_APP', $detected);
}

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'u367896320_camara');
define('DB_USER', 'root');
define('DB_PASS', '');
date_default_timezone_set('America/Guayaquil');
// define('DB_HOST', 'srv1147.hstgr.io');
// define('DB_NAME', 'u367896320_camara');
// define('DB_USER', 'u367896320_camara');
// define('DB_PASS', 'cacG1404!');

?>