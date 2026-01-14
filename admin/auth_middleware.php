<?php
/**
 * Middleware de autenticación y autorización
 * Verifica que el usuario esté autenticado y tenga el rol adecuado
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verificar si el usuario está autenticado
 */
function verificarAutenticacion() {
    // Verificar si existe sesión de usuario válida
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || 
        !isset($_SESSION['user_email']) || empty($_SESSION['user_email'])) {
        // Limpiar sesión corrupta
        session_unset();
        session_destroy();
        
        // No autenticado - redirigir a login
        header('Location: ../login.php?unauthorized=1');
        exit;
    }
    
    // Verificar timeout de sesión (opcional - 2 horas, solo si no marcó "recordarme")
    if (!isset($_SESSION['remember_me']) || $_SESSION['remember_me'] !== true) {
        $timeout = 2 * 60 * 60; // 2 horas en segundos
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $timeout)) {
            // Sesión expirada
            session_unset();
            session_destroy();
            header('Location: ../login.php?timeout=1&message=' . urlencode('Tu sesión ha expirado'));
            exit;
        }
    }
    
    // Verificar el estado del usuario en la base de datos (por si fue desactivado después de loguearse)
    require_once __DIR__ . '/../config.php';
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT estado FROM usuarios WHERE id = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || $user['estado'] == 0) {
            // Usuario desactivado o en revisión
            session_unset();
            session_destroy();
            header('Location: ../login.php?info=' . urlencode('Su cuenta ha sido desactivada o está en proceso de revisión. Contacte al administrador para más información.'));
            exit;
        }
    } catch (PDOException $e) {
        // En caso de error de BD, permitir continuar pero loguear el error
        error_log("Error verificando estado de usuario: " . $e->getMessage());
    }
}

/**
 * Verificar si el usuario tiene el rol requerido
 * @param array $rolesPermitidos Array de roles permitidos
 */
function verificarRol($rolesPermitidos = []) {
    if (empty($rolesPermitidos)) {
        return; // Sin restricción de rol
    }
    
    $rolUsuario = $_SESSION['user_rol'] ?? null;
    
    if (!in_array($rolUsuario, $rolesPermitidos)) {
        // No tiene el rol adecuado
        http_response_code(403);
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Acceso Denegado</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }
                .error-container {
                    background: white;
                    padding: 3rem;
                    border-radius: 1rem;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    text-align: center;
                    max-width: 500px;
                }
                .error-code {
                    font-size: 6rem;
                    font-weight: bold;
                    color: #667eea;
                    margin: 0;
                    line-height: 1;
                }
                h1 {
                    color: #333;
                    margin: 1rem 0;
                }
                p {
                    color: #666;
                    margin: 1rem 0 2rem;
                }
                .btn {
                    display: inline-block;
                    padding: 0.75rem 2rem;
                    background: #667eea;
                    color: white;
                    text-decoration: none;
                    border-radius: 0.5rem;
                    transition: background 0.3s;
                }
                .btn:hover {
                    background: #764ba2;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-code">403</div>
                <h1>Acceso Denegado</h1>
                <p>No tienes permisos para acceder a esta página.</p>
                <a href="../login.php" class="btn">Volver al inicio</a>
            </div>
        </body>
        </html>
        ';
        exit;
    }
}

/**
 * Obtener información del usuario actual
 */
function getUsuarioActual() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'nombre' => $_SESSION['user_nombre'] ?? '',
        'apellido' => $_SESSION['user_apellido'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'rol' => $_SESSION['user_rol'] ?? null,
        'img' => $_SESSION['user_img'] ?? null
    ];
}

/**
 * Obtener nombre del rol
 */
function getNombreRol($rol) {
    $roles = [
        1 => 'Administrador',
        2 => 'Socio',
        10 => 'Presidente',
        11 => 'Vicepresidente',
        12 => 'Secretaria',
        20 => 'Delegado'
    ];
    return $roles[$rol] ?? 'Desconocido';
}

// Verificar autenticación automáticamente al incluir este archivo
verificarAutenticacion();
