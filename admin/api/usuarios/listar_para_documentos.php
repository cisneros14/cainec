<?php
// admin/api/usuarios/listar_para_documentos.php
require_once __DIR__ . '/../../../config.php';

// Iniciar sesión para saber quién es el usuario actual y excluirlo
session_start();
$current_user_id = $_SESSION['user_id'] ?? 0;

header('Content-Type: application/json; charset=utf-8');

$tipo = $_GET['tipo'] ?? 'socios'; // 'socios' o 'directiva'

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    $usuarios = [];

    if ($tipo === 'directiva') {
        // CASO 1: DIRECTIVA (Usuarios con SMTP configurado)
        // Regla: Usar el email del SMTP, no el personal. Excluir al usuario actual.
        $sql = "SELECT 
                    u.id, 
                    u.nombre, 
                    u.apellido, 
                    -- Aquí está el truco: Usamos el correo configurado en SMTP. 
                    -- Si from_email está vacío, usamos el username del smtp.
                    COALESCE(NULLIF(s.from_email, ''), s.smtp_username) as email,
                    -- También podemos tomar el nombre configurado en el SMTP si existe
                    COALESCE(NULLIF(s.from_name, ''), CONCAT(u.nombre, ' ', u.apellido)) as nombre_mostrar
                FROM usuarios u 
                INNER JOIN smtp_config s ON u.id = s.user_id 
                WHERE s.is_active = 1 
                AND u.id != ? -- <--- EXCLUIMOS AL USUARIO ACTUAL
                GROUP BY u.id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$current_user_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formateamos para que el frontend lo entienda fácil
        foreach($data as $row) {
            $usuarios[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'], 
                'apellido' => $row['apellido'],
                'email' => $row['email'], // Este será el email SMTP (ej: presidencia@...)
                'label_nombre' => $row['nombre_mostrar'] // Nombre para mostrar en el chip
            ];
        }

    } else {
        // CASO 2: SOCIOS (Todos los usuarios activos)
        // Regla: Usar email de registro. Excluir al usuario actual.
        $sql = "SELECT id, nombre, apellido, email 
                FROM usuarios 
                WHERE estado = 1 
                AND id != ? -- <--- EXCLUIMOS AL USUARIO ACTUAL
                ORDER BY nombre ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$current_user_id]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode(['success' => true, 'data' => $usuarios]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>