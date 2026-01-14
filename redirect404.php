<?php
// script que usa la constante URL_APP para redirigir a la página de error
require_once __DIR__ . '/config.php';

// Aseguramos que URL_APP termina con '/'
$base = rtrim(URL_APP, '/') . '/';
$target = $base . 'error.php';

// Redirigir con código 302 (temporal). Cambia a 301 si se desea permanente.
header('Location: ' . $target, true, 302);
exit;
?>