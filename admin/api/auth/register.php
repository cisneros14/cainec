<?php
header('Content-Type: application/json');

// Ajustar la ruta de config.php según la estructura de carpetas
$configPath = __DIR__ . '/../../../config.php';

if (file_exists($configPath)) {
    require_once $configPath;
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de configuración: No se encuentra config.php']);
    exit;
}

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Use POST.']);
    exit;
}

// Obtener datos
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// --- FUNCIONES DE LIMPIEZA Y VALIDACIÓN ---

function cleanInt($val)
{
    if ($val === '' || $val === null)
        return null;
    return (int) $val;
}

function cleanDate($val)
{
    if ($val === '' || $val === null)
        return null;
    return $val;
}

function cleanStr($val)
{
    if ($val === '' || $val === null)
        return null;
    return trim($val);
}

function validateRequired($data, $fields)
{
    $missing = [];
    foreach ($fields as $field) {
        // Verifica si el campo no existe, es null, o es string vacío.
        // Permite '0' (string) o 0 (int) como valores válidos.
        if (!isset($data[$field]) || ($data[$field] === '' || $data[$field] === null)) {
            $missing[] = $field;
        }
    }
    return $missing;
}

// Mapeo de nombres de campos a etiquetas amigables
$fieldLabels = [
    // Comunes
    'nombre' => 'Nombre',
    'apellido' => 'Apellido',
    'email' => 'Correo Electrónico',
    'password' => 'Contraseña',
    'tipo_socio' => 'Tipo de Perfil',
    'cedula_ruc' => 'Cédula / RUC',
    'telefono_contacto' => 'Teléfono de Contacto',
    'provincia' => 'Provincia',
    'ciudad' => 'Ciudad',

    // Natural
    'fecha_nacimiento' => 'Fecha de Nacimiento',
    'genero' => 'Género',
    'nivel_educacion' => 'Nivel de Educación',
    'registro_profesional' => 'Registro Profesional',
    'formacion' => 'Formación Académica',
    'certificaciones' => 'Certificaciones',
    'habilidades' => 'Habilidades',
    'actividad' => 'Actividad Económica',
    'ciudad_operaciones' => 'Ciudad de Operaciones',
    'plazas_trabajo_generadas' => 'Plazas de Trabajo Generadas',

    // Juridica / Organizacion
    'empresa' => 'Nombre Comercial / Organización',
    'nombre_juridico' => 'Razón Social',
    'representante_legal' => 'Representante Legal',
    'cargo' => 'Cargo de quien registra',
    'inicio_actividades' => 'Fecha Inicio Actividades / Constitución',
    'sector' => 'Sector',
    'director_ejecutivo' => 'Director Ejecutivo',
    'numero_miembros' => 'Número de Miembros'
];

// 1. Validaciones Comunes
$commonFields = ['nombre', 'apellido', 'email', 'password', 'tipo_socio', 'cedula_ruc', 'telefono_contacto', 'provincia', 'ciudad'];
$missingCommon = validateRequired($input, $commonFields);

if (!empty($missingCommon)) {
    $missingLabels = array_map(function ($field) use ($fieldLabels) {
        return $fieldLabels[$field] ?? $field;
    }, $missingCommon);

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor complete los siguientes campos obligatorios: ' . implode(', ', $missingLabels)]);
    exit;
}

// Validar formato de email
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El formato del correo electrónico no es válido']);
    exit;
}

// 2. Validaciones Específicas por Tipo de Socio
$tipo_socio = $input['tipo_socio'];
$missingSpecific = [];

if ($tipo_socio === 'natural') {
    // Definir campos obligatorios para Persona Natural
    $specificRequired = [
        'fecha_nacimiento',
        'genero',
        'nivel_educacion',
        'formacion', // Asumido obligatorio
        'habilidades', // Asumido obligatorio
        'actividad',
        'ciudad_operaciones',
        'plazas_trabajo_generadas'
    ];
    // Opcionales: registro_profesional, certificaciones

    $missingSpecific = validateRequired($input, $specificRequired);

} elseif ($tipo_socio === 'juridica') {
    // Definir campos obligatorios para Persona Jurídica
    $specificRequired = [
        'empresa',
        'nombre_juridico',
        'representante_legal',
        'cargo',
        'actividad',
        'inicio_actividades',
        'plazas_trabajo_generadas'
    ];
    $missingSpecific = validateRequired($input, $specificRequired);

} elseif ($tipo_socio === 'organizacion') {
    // Definir campos obligatorios para Organización
    $specificRequired = [
        'empresa',
        'nombre_juridico',
        'sector',
        'representante_legal',
        'director_ejecutivo',
        'cargo',
        'numero_miembros',
        'inicio_actividades'
    ];
    $missingSpecific = validateRequired($input, $specificRequired);
}

if (!empty($missingSpecific)) {
    $missingLabels = array_map(function ($field) use ($fieldLabels) {
        return $fieldLabels[$field] ?? $field;
    }, $missingSpecific);

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor complete los campos específicos: ' . implode(', ', $missingLabels)]);
    exit;
}


// 3. Conexión a Base de Datos
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor (Base de datos)']);
    exit;
}

// 4. Verificar duplicados (Email o Cédula/RUC)
try {
    $stmt = $pdo->prepare("SELECT id, email, cedula_ruc FROM usuarios WHERE email = ? OR cedula_ruc = ?");
    $stmt->execute([$input['email'], $input['cedula_ruc']]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        http_response_code(409);
        if ($existing['email'] === $input['email']) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'La Cédula/RUC ya está registrada']);
        }
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al verificar duplicados']);
    exit;
}

// 5. Preparar datos para inserción
$password_hash = password_hash($input['password'], PASSWORD_DEFAULT);
$usuario = explode('@', $input['email'])[0];

// Asegurar unicidad del username
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);
if ($stmt->fetchColumn() > 0) {
    $usuario .= '_' . time();
}

$estado = 'pendiente';

// Mapeo de campos base (TODOS sanitizados)
$params = [
    ':nombre' => cleanStr($input['nombre']),
    ':apellido' => cleanStr($input['apellido']),
    ':cedula_ruc' => cleanStr($input['cedula_ruc']),
    ':email' => cleanStr($input['email']),
    ':usuario' => $usuario,
    ':password_hash' => $password_hash,
    ':tipo_socio' => $tipo_socio,
    ':estado' => $estado,
    ':telefono_contacto' => cleanStr($input['telefono_contacto']),
    ':provincia' => cleanStr($input['provincia']),
    ':ciudad' => cleanStr($input['ciudad']),
    // Campos opcionales
    ':telefono_contacto2' => cleanStr($input['telefono_contacto2'] ?? null),
    ':direccion' => cleanStr($input['direccion'] ?? null),
    ':pagina_web' => cleanStr($input['pagina_web'] ?? null),
    ':facebook' => cleanStr($input['facebook'] ?? null),
    ':instagram' => cleanStr($input['instagram'] ?? null),
    ':linkedin' => cleanStr($input['linkedin'] ?? null),
    ':descripcion' => cleanStr($input['descripcion'] ?? null),
    ':img_url' => cleanStr($input['img_url'] ?? null),
];

// 6. Campos específicos según tipo de socio (TODOS sanitizados por tipo)
$extra_fields = [];

if ($tipo_socio === 'natural') {
    $extra_fields = [
        'fecha_nacimiento' => cleanDate($input['fecha_nacimiento'] ?? null),
        'genero' => cleanStr($input['genero'] ?? null),
        'nivel_educacion' => cleanStr($input['nivel_educacion'] ?? null),
        'formacion' => cleanStr($input['formacion'] ?? null),
        'registro_profesional' => cleanStr($input['registro_profesional'] ?? null),
        'certificaciones' => cleanStr($input['certificaciones'] ?? null),
        'habilidades' => cleanStr($input['habilidades'] ?? null),
        'actividad' => cleanStr($input['actividad'] ?? null),
        'ciudad_operaciones' => cleanStr($input['ciudad_operaciones'] ?? null),
        'plazas_trabajo_generadas' => cleanInt($input['plazas_trabajo_generadas'] ?? null),
    ];
} elseif ($tipo_socio === 'juridica') {
    $extra_fields = [
        'empresa' => cleanStr($input['empresa'] ?? null),
        'nombre_juridico' => cleanStr($input['nombre_juridico'] ?? null),
        'representante_legal' => cleanStr($input['representante_legal'] ?? null),
        'actividad' => cleanStr($input['actividad'] ?? null),
        'inicio_actividades' => cleanDate($input['inicio_actividades'] ?? null),
        'plazas_trabajo_generadas' => cleanInt($input['plazas_trabajo_generadas'] ?? null),
        'cargo' => cleanStr($input['cargo'] ?? null),
        'directiva' => isset($input['directiva']) ? 1 : 0,
    ];
} elseif ($tipo_socio === 'organizacion') {
    $extra_fields = [
        'empresa' => cleanStr($input['empresa'] ?? null),
        'nombre_juridico' => cleanStr($input['nombre_juridico'] ?? null),
        'sector' => cleanStr($input['sector'] ?? null),
        'representante_legal' => cleanStr($input['representante_legal'] ?? null),
        'director_ejecutivo' => cleanStr($input['director_ejecutivo'] ?? null),
        'cargo' => cleanStr($input['cargo'] ?? null),
        'numero_miembros' => cleanInt($input['numero_miembros'] ?? null),
        'inicio_actividades' => cleanDate($input['inicio_actividades'] ?? null),
    ];
}

// Construir la consulta SQL dinámicamente
$columns = [];
$placeholders = [];

foreach ($params as $key => $value) {
    $colName = ltrim($key, ':');
    $columns[] = $colName;
    $placeholders[] = $key;
}

foreach ($extra_fields as $key => $value) {
    $columns[] = $key;
    $placeholders[] = ":$key";
    $params[":$key"] = $value;
}

$sql = "INSERT INTO usuarios (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";

// 7. Ejecutar inserción
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $userId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Registro exitoso. Tu cuenta está pendiente de aprobación.',
        'user_id' => $userId
    ]);

} catch (PDOException $e) {
    error_log("Registration SQL Error: " . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar usuario en la base de datos. ' . $e->getMessage()
    ]);
}
?>