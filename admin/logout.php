<?php
/**
 * Logout - Cerrar sesión de usuario
 */

session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Borrar la cookie de sesión si existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destruir la sesión
session_destroy();

// Redirigir al login con mensaje
header('Location: ../login.php?info=' . urlencode('Has cerrado sesión correctamente'));
exit;
