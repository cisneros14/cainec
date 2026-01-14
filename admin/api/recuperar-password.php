<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../config.php';

// Función para enviar email usando SMTP con PHPMailer
function enviarEmail($destinatario, $asunto, $mensaje) {
    require_once __DIR__ . '/../../vendor/PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../../vendor/PHPMailer-master/src/SMTP.php';
    require_once __DIR__ . '/../../vendor/PHPMailer-master/src/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cisnerosgranda14@gmail.com';
        $mail->Password = 'baet nhhk kgkg feof';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Remitente y destinatario
        $mail->setFrom('cisnerosgranda14@gmail.com', 'CAINEC');
        $mail->addAddress($destinatario);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$user_login = trim($_POST['user_login'] ?? '');

if (empty($user_login)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa tu usuario o email'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Buscar usuario por username o email
    $stmt = $pdo->prepare("
        SELECT id, nombre, apellido, email 
        FROM usuarios 
        WHERE (usuario = ? OR email = ?) AND estado = 1
        LIMIT 1
    ");
    $stmt->execute([$user_login, $user_login]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        // Por seguridad, no revelar si el usuario existe o no
        echo json_encode([
            'success' => true,
            'message' => 'Si el usuario existe, recibirás un correo con instrucciones para recuperar tu contraseña'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Generar token único de 6 dígitos
    $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expira_en = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora
    
    // Guardar código en la base de datos
    $stmt = $pdo->prepare("
        UPDATE usuarios 
        SET password_reset_code = ?, password_reset_expires = ?
        WHERE id = ?
    ");
    $stmt->execute([$codigo, $expira_en, $usuario['id']]);
    
    // Crear enlace de recuperación
    $url_recuperacion = "http://" . $_SERVER['HTTP_HOST'] . "/cainec/reset-password.php?code=" . $codigo . "&email=" . urlencode($usuario['email']);
    
    // Crear mensaje HTML
    $mensaje = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #0e2c5b 0%, #344d6c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .code { font-size: 32px; font-weight: bold; color: #0e2c5b; text-align: center; padding: 20px; background: white; border-radius: 5px; letter-spacing: 5px; margin: 20px 0; }
            .button { display: inline-block; padding: 15px 30px; background: #0e2c5b; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Recuperación de Contraseña</h1>
            </div>
            <div class='content'>
                <p>Hola <strong>{$usuario['nombre']} {$usuario['apellido']}</strong>,</p>
                <p>Hemos recibido una solicitud para recuperar la contraseña de tu cuenta en CAINEC.</p>
                <p>Tu código de recuperación es:</p>
                <div class='code'>{$codigo}</div>
                <p>O haz clic en el siguiente botón para restablecer tu contraseña:</p>
                <div style='text-align: center;'>
                    <a href='{$url_recuperacion}' class='button !text-white'>Restablecer Contraseña</a>
                </div>
                <p><strong>Este código expirará en 1 hora.</strong></p>
                <p>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " CAINEC - Cámara Inmobiliaria Ecuatoriana</p>
                <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Enviar correo (en segundo plano, no esperamos respuesta)
    enviarEmail($usuario['email'], 'Recuperación de Contraseña - CAINEC', $mensaje);
    
    // Por seguridad, siempre devolver el mismo mensaje
    echo json_encode([
        'success' => true,
        'message' => 'Si el usuario existe, recibirás un correo con instrucciones para recuperar tu contraseña',
        'redirect' => true
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Por seguridad, no revelar detalles del error
    echo json_encode([
        'success' => true,
        'message' => 'Si el usuario existe, recibirás un correo con instrucciones para recuperar tu contraseña',
        'redirect' => true
    ], JSON_UNESCAPED_UNICODE);
}
