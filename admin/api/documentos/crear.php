<?php
// admin/api/documentos/crear.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

ini_set('max_execution_time', 300); 
ini_set('memory_limit', '256M');

header('Content-Type: application/json; charset=utf-8');

$rootPath = __DIR__ . '/../../../';
require_once $rootPath . 'vendor/autoload.php';
require_once $rootPath . 'config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$usuario_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// 1. RECUPERAR DATOS
$tipo_id = $_POST['tipo_id'] ?? null;
$asunto = $_POST['asunto'] ?? null;
$cuerpo = $_POST['cuerpo'] ?? '';
$destinatarios_json = $_POST['destinatarios'] ?? '[]';
$destinatarios = json_decode($destinatarios_json, true);

// --- AQUÍ RECUPERAMOS EL VÍNCULO DEL PADRE ---
$documento_padre_id = !empty($_POST['documento_padre_id']) ? $_POST['documento_padre_id'] : null;

if (!$tipo_id || !$asunto || empty($destinatarios)) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-05:00'"
    ]);
    
    $pdo->beginTransaction();

    // 2. GENERAR CÓDIGO
    $stmtTipo = $pdo->prepare("SELECT * FROM tipos_documento WHERE id = ? FOR UPDATE");
    $stmtTipo->execute([$tipo_id]);
    $tipo = $stmtTipo->fetch(PDO::FETCH_ASSOC);
    
    if (!$tipo) throw new Exception("Tipo de documento inválido");

    $anioActual = date('Y');
    $nuevaSecuencia = $tipo['secuencia_actual'] + 1;
    $codigo = sprintf("%s-%s-%04d", $tipo['prefijo'], $anioActual, $nuevaSecuencia);
    
    $pdo->prepare("UPDATE tipos_documento SET secuencia_actual = ?, anio = ? WHERE id = ?")
        ->execute([$nuevaSecuencia, $anioActual, $tipo_id]);

    // 3. INSERTAR DOCUMENTO (CON PADRE ID PARA TRAZABILIDAD)
    $sqlDoc = "INSERT INTO documentos 
               (codigo, tipo_id, asunto, cuerpo, creador_id, estado, fecha_creacion, documento_padre_id) 
               VALUES (?, ?, ?, ?, ?, 'ENVIADO', NOW(), ?)";
               
    $stmtDoc = $pdo->prepare($sqlDoc);
    $stmtDoc->execute([
        $codigo, 
        $tipo_id, 
        $asunto, 
        $cuerpo, 
        $usuario_id,
        $documento_padre_id // <--- Esto es lo que faltaba guardar
    ]);
    
    $documento_id = $pdo->lastInsertId();

    // 4. PROCESAR ADJUNTOS
    $adjuntos_procesados = []; 
    $uploadDir = $rootPath . 'uploads/documentos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (isset($_FILES['archivos'])) {
        $count = count($_FILES['archivos']['name']);
        for ($i = 0; $i < $count; $i++) { 
            if ($_FILES['archivos']['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['archivos']['tmp_name'][$i];
                $name = basename($_FILES['archivos']['name'][$i]);
                $finalName = time() . '_' . rand(100, 999) . '_' . $name;
                $targetPath = $uploadDir . $finalName;
                
                if (move_uploaded_file($tmpName, $targetPath)) {
                    $pdo->prepare("INSERT INTO adjuntos (documento_id, user_id, nombre_original, ruta_archivo, tipo_mime) VALUES (?, ?, ?, ?, ?)")
                        ->execute([$documento_id, $usuario_id, $name, 'uploads/documentos/' . $finalName, $_FILES['archivos']['type'][$i]]);
                    $adjuntos_procesados[] = ['path' => $targetPath, 'name' => $name];
                }
            }
        }
    }

    // 5. CONFIGURAR SMTP
    $stmtSmtp = $pdo->prepare("SELECT * FROM smtp_config WHERE user_id = ? AND is_active = 1 LIMIT 1");
    $stmtSmtp->execute([$usuario_id]);
    $smtpConfig = $stmtSmtp->fetch(PDO::FETCH_ASSOC);
    if (!$smtpConfig) {
        $stmtDef = $pdo->query("SELECT * FROM smtp_config WHERE is_default = 1 AND is_active = 1 LIMIT 1");
        $smtpConfig = $stmtDef->fetch(PDO::FETCH_ASSOC);
    }
    if (!$smtpConfig) throw new Exception("No hay configuración SMTP.");

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $smtpConfig['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtpConfig['smtp_username'];
    $mail->Password = $smtpConfig['smtp_password'];
    $mail->Port = (int)$smtpConfig['smtp_port'];
    $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
    if ($smtpConfig['encryption'] === 'ssl') $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    elseif ($smtpConfig['encryption'] === 'tls') $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    else { $mail->SMTPAutoTLS = false; $mail->SMTPSecure = ''; }

    $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name'] ?: 'Sistema Documental');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    foreach ($adjuntos_procesados as $adj) {
        $mail->addAttachment($adj['path'], $adj['name']);
    }

    // 6. ENVIAR Y CREAR MOVIMIENTOS
    foreach ($destinatarios as $dest) {
        $emailDestino = $dest['email'];
        $dest_id = !empty($dest['id']) ? $dest['id'] : null;
        
        $pdo->prepare("INSERT INTO movimientos (documento_id, remitente_id, destinatario_id, destinatario_email, accion, fecha_envio) VALUES (?, ?, ?, ?, 'ENVIADO', NOW())")
            ->execute([$documento_id, $usuario_id, $dest_id, $emailDestino]);
        
        $mov_id = $pdo->lastInsertId();

        $mail->clearAddresses();
        $mail->addAddress($emailDestino);
        $mail->Subject = "$codigo: $asunto";
        
        $dominio = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'], 3);
        $link = $dominio . "/ver_documento.php?t=" . base64_encode($mov_id);

        $mail->Body = "<h2>Nuevo Documento: $codigo</h2><p>Asunto: $asunto</p><br><a href='$link' style='background:#0e2c5b; color:white; padding:10px; text-decoration:none;'>Ver Documento en Línea</a>";

        try { $mail->send(); } catch (Exception $e) { /* Log error */ }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'codigo' => $codigo]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>