<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

// Crear conexión PDO
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$pdo = new PDO($dsn, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Obtener datos
$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$cedula_ruc = trim($_POST['cedula_ruc'] ?? '');
$cargo = trim($_POST['cargo'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$licencia = trim($_POST['licencia'] ?? '');
$telefono_contacto = trim($_POST['telefono_contacto'] ?? '');
$telefono_contacto2 = trim($_POST['telefono_contacto2'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$provincia = trim($_POST['provincia'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$pagina_web = trim($_POST['pagina_web'] ?? '');
$facebook = trim($_POST['facebook'] ?? '');
$instagram = trim($_POST['instagram'] ?? '');
$linkedin = trim($_POST['linkedin'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? ''); // Opcional en edición
$rol = intval($_POST['rol'] ?? 0);
$estado = intval($_POST['estado'] ?? 1);
$directiva = intval($_POST['directiva'] ?? 0);
$img_url = trim($_POST['img_url'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$formacion = trim($_POST['formacion'] ?? '');
$habilidades = trim($_POST['habilidades'] ?? '');
$certificaciones = trim($_POST['certificaciones'] ?? '');

// Validaciones
$errores = [];

if ($id <= 0) {
    $errores[] = 'ID de usuario inválido';
}

if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 20) {
    $errores[] = 'El nombre es obligatorio y debe tener entre 2 y 20 caracteres';
}

if (empty($apellido) || strlen($apellido) < 2 || strlen($apellido) > 20) {
    $errores[] = 'El apellido es obligatorio y debe tener entre 2 y 20 caracteres';
}

// Validar usuario y email solo si se proporcionan (desde admin/usuarios.php)
// Desde perfil.php no se envían porque están bloqueados
if (!empty($usuario)) {
    if (strlen($usuario) < 3 || strlen($usuario) > 64) {
        $errores[] = 'El usuario debe tener entre 3 y 64 caracteres';
    }
}

if (!empty($email)) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 64) {
        $errores[] = 'El email debe ser válido';
    }
}

if (!empty($errores)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errores)], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Verificar que el usuario existe y obtener imagen antigua
    $stmt = $pdo->prepare("SELECT id, img_url FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuarioActual = $stmt->fetch();
    if (!$usuarioActual) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $img_antigua = $usuarioActual['img_url'];

    // Verificar unicidad de usuario solo si se proporciona (excluyendo el actual)
    if (!empty($usuario)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? AND id != ?");
        $stmt->execute([$usuario, $id]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El usuario ya existe'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // Verificar unicidad de email solo si se proporciona (excluyendo el actual)
    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // Eliminar imagen antigua si se proporciona una nueva
    if (!empty($img_url) && !empty($img_antigua) && $img_url !== $img_antigua) {
        $ruta_imagen_antigua = __DIR__ . '/../../../' . $img_antigua;
        if (file_exists($ruta_imagen_antigua)) {
            unlink($ruta_imagen_antigua);
        }
    }

    // Determinar si se actualizan usuario y email (desde admin) o no (desde perfil)
    $actualizarCredenciales = !empty($usuario) && !empty($email);

    // Si se proporciona nueva contraseña, actualizarla
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($actualizarCredenciales) {
            // Desde admin/usuarios.php - actualizar todo incluyendo usuario, email y password
            $stmt = $pdo->prepare("
                UPDATE usuarios SET 
                    nombre = ?, apellido = ?, cedula_ruc = ?, cargo = ?, empresa = ?, licencia = ?,
                    telefono_contacto = ?, telefono_contacto2 = ?, direccion = ?, provincia = ?, ciudad = ?, pagina_web = ?,
                    facebook = ?, instagram = ?, linkedin = ?,
                    usuario = ?, email = ?, password_hash = ?, rol = ?, estado = ?, directiva = ?, img_url = ?,
                    descripcion = ?, formacion = ?, habilidades = ?, certificaciones = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $nombre,
                $apellido,
                $cedula_ruc,
                $cargo,
                $empresa,
                $licencia,
                $telefono_contacto,
                $telefono_contacto2,
                $direccion,
                $provincia,
                $ciudad,
                $pagina_web,
                $facebook,
                $instagram,
                $linkedin,
                $usuario,
                $email,
                $password_hash,
                $rol,
                $estado,
                $directiva,
                $img_url,
                $descripcion,
                $formacion,
                $habilidades,
                $certificaciones,
                $id
            ]);
        } else {
            // Desde perfil.php - actualizar sin tocar usuario ni email, solo password
            $stmt = $pdo->prepare("
                UPDATE usuarios SET 
                    nombre = ?, apellido = ?, cedula_ruc = ?, cargo = ?, empresa = ?, licencia = ?,
                    telefono_contacto = ?, telefono_contacto2 = ?, direccion = ?, provincia = ?, ciudad = ?, pagina_web = ?,
                    facebook = ?, instagram = ?, linkedin = ?,
                    password_hash = ?, img_url = ?,
                    descripcion = ?, formacion = ?, habilidades = ?, certificaciones = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $nombre,
                $apellido,
                $cedula_ruc,
                $cargo,
                $empresa,
                $licencia,
                $telefono_contacto,
                $telefono_contacto2,
                $direccion,
                $provincia,
                $ciudad,
                $pagina_web,
                $facebook,
                $instagram,
                $linkedin,
                $password_hash,
                $img_url,
                $descripcion,
                $formacion,
                $habilidades,
                $certificaciones,
                $id
            ]);
        }
    } else {
        // Actualizar sin cambiar contraseña
        if ($actualizarCredenciales) {
            // Desde admin/usuarios.php - actualizar todo incluyendo usuario y email
            $stmt = $pdo->prepare("
                UPDATE usuarios SET 
                    nombre = ?, apellido = ?, cedula_ruc = ?, cargo = ?, empresa = ?, licencia = ?,
                    telefono_contacto = ?, telefono_contacto2 = ?, direccion = ?, provincia = ?, ciudad = ?, pagina_web = ?,
                    facebook = ?, instagram = ?, linkedin = ?,
                    usuario = ?, email = ?, rol = ?, estado = ?, directiva = ?, img_url = ?,
                    descripcion = ?, formacion = ?, habilidades = ?, certificaciones = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $nombre,
                $apellido,
                $cedula_ruc,
                $cargo,
                $empresa,
                $licencia,
                $telefono_contacto,
                $telefono_contacto2,
                $direccion,
                $provincia,
                $ciudad,
                $pagina_web,
                $facebook,
                $instagram,
                $linkedin,
                $usuario,
                $email,
                $rol,
                $estado,
                $directiva,
                $img_url,
                $descripcion,
                $formacion,
                $habilidades,
                $certificaciones,
                $id
            ]);
        } else {
            // Desde perfil.php - actualizar sin tocar usuario, email ni password
            $stmt = $pdo->prepare("
                UPDATE usuarios SET 
                    nombre = ?, apellido = ?, cedula_ruc = ?, cargo = ?, empresa = ?, licencia = ?,
                    telefono_contacto = ?, telefono_contacto2 = ?, direccion = ?, provincia = ?, ciudad = ?, pagina_web = ?,
                    facebook = ?, instagram = ?, linkedin = ?,
                    img_url = ?,
                    descripcion = ?, formacion = ?, habilidades = ?, certificaciones = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $nombre,
                $apellido,
                $cedula_ruc,
                $cargo,
                $empresa,
                $licencia,
                $telefono_contacto,
                $telefono_contacto2,
                $direccion,
                $provincia,
                $ciudad,
                $pagina_web,
                $facebook,
                $instagram,
                $linkedin,
                $img_url,
                $descripcion,
                $formacion,
                $habilidades,
                $certificaciones,
                $id
            ]);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Usuario actualizado exitosamente'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar usuario: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
