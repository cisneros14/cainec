<?php
// api/smtp_config/probar.php

// 1. IMPORTACIONES
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;
use PHPMailer\PHPMailer\SMTP;

// 2. CONFIGURACIÓN
ob_start();
define('DEBUG_LOG_FILE', __DIR__ . '/debug_smtp.log');

function writeLog($message) {
    $date = date('Y-m-d H:i:s');
    file_put_contents(DEBUG_LOG_FILE, "[$date] $message\n", FILE_APPEND);
}

file_put_contents(DEBUG_LOG_FILE, "\n--- PRUEBA CON IMAP ---\n", FILE_APPEND);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR) {
        writeLog("FATAL ERROR: " . $error['message']);
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error Fatal. Revisa el log.']);
    }
});

header('Content-Type: application/json; charset=utf-8');
$response = ['success' => false, 'message' => 'Error desconocido'];

try {
    // 3. CARGA DE DEPENDENCIAS
    $baseDir = __DIR__ . '/../../';
    $possiblePaths = [
        __DIR__ . '/../../../vendor/autoload.php',
        $baseDir . 'vendor/autoload.php'
    ];
    
    $autoloadFound = false;
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $configPath = dirname($path) . '/config.php';
            if (file_exists($configPath)) require_once $configPath;
            else if (file_exists(__DIR__ . '/../../../config.php')) require_once __DIR__ . '/../../../config.php';
            
            $autoloadFound = true;
            break;
        }
    }

    if (!$autoloadFound) throw new Exception("No se encuentra vendor/autoload.php");

    // 4. DATOS Y CONEXIÓN
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Método inválido.');
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (empty($data['id']) || empty($data['email_destino'])) throw new Exception('Faltan datos.');

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $stmt = $pdo->prepare("SELECT * FROM smtp_config WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $data['id']]);
    $config = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$config) throw new Exception('Configuración no encontrada.');

    // 5. PHPMAILER
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp_username'];
    $mail->Password   = $config['smtp_password'];
    $mail->Port       = (int)$config['smtp_port'];
    $mail->Timeout    = 15;
    
    // Opciones SSL relajadas para pruebas
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true
        )
    );

    // Encriptación
    if ($config['encryption'] === 'ssl') $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    elseif ($config['encryption'] === 'tls') $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    else { $mail->SMTPAutoTLS = false; $mail->SMTPSecure = ''; }

    $fromName = !empty($config['from_name']) ? $config['from_name'] : 'Prueba Sistema';
    $mail->setFrom($config['from_email'], $fromName);
    $mail->addAddress($data['email_destino']);

    $mail->isHTML(true);
    $mail->Subject = 'Prueba con Copia en Enviados - ' . date('H:i');
    $mail->Body    = '<h1>Correo de Prueba</h1><p>Si ves esto, el SMTP funcionó. Revisa tu carpeta de Enviados para ver si funcionó el IMAP.</p>';

    writeLog("Enviando correo SMTP...");
    $mail->send();
    writeLog("¡Correo enviado!");

    // =================================================================
    // [NUEVO IMAP] GUARDAR COPIA EN CARPETA DE ENVIADOS
    // =================================================================
    if (function_exists('imap_open')) {
        writeLog("Iniciando proceso IMAP...");

        // 1. Deducir host IMAP (Cambiamos smtp.hostinger.com por imap.hostinger.com)
        // Esto funciona para Hostinger y cPanel. Para Gmail sería 'imap.gmail.com'
        $imapHost = str_replace('smtp.', 'imap.', $config['smtp_host']);
        
        // 2. Definir carpeta de destino
        // Hostinger usa "INBOX.Sent". Otros usan "Sent" o "Enviados".
        $folderName = "INBOX.Sent"; 
        
        // 3. Cadena de conexión IMAP {host:993/imap/ssl}Carpeta
        $mailbox = "{" . $imapHost . ":993/imap/ssl}" . $folderName;
        
        writeLog("Intentando conectar IMAP a: $mailbox");

        // 4. Abrir conexión (Usamos @ para evitar warnings feos en el JSON)
        $imapStream = @imap_open($mailbox, $config['smtp_username'], $config['smtp_password']);

        if ($imapStream) {
            writeLog("Conexión IMAP exitosa. Guardando mensaje...");
            
            // 5. Obtener el string completo del correo enviado
            $msg = $mail->getSentMIMEMessage();
            
            // 6. Subir mensaje a la carpeta
            if (imap_append($imapStream, $mailbox, $msg)) {
                writeLog("¡ÉXITO! Copia guardada en la carpeta de Enviados.");
            } else {
                writeLog("Error al guardar (imap_append falló).");
            }
            
            imap_close($imapStream);
        } else {
            // Capturar error si falla la conexión
            $imapErrors = imap_last_error();
            writeLog("FALLO IMAP: No se pudo conectar. Error: " . $imapErrors);
            // NOTA: No lanzamos excepción para que el usuario vea el éxito del envío SMTP al menos.
        }
    } else {
        writeLog("La extensión IMAP de PHP no está habilitada.");
    }
    // =================================================================

    $response['success'] = true;
    $response['message'] = 'Correo enviado correctamente.';

} catch (Exception $e) {
    $msg = $e->getMessage();
    writeLog("ERROR: " . $msg);
    $response['success'] = false;
    if (strpos($msg, 'connect()') !== false) {
        $response['message'] = "TIMEOUT: Revisa tu antivirus/firewall.";
    } else {
        $response['message'] = "Error: " . $msg;
    }
}

ob_end_clean();
echo json_encode($response);
exit;
?>