<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nombre']) || !isset($data['tipo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO socios (tipo, nombre, cargo, imagen, descripcion_corta, descripcion_completa, servicios, beneficios, educacion, email, telefono, website, linkedin, orden) 
            VALUES (:tipo, :nombre, :cargo, :imagen, :descripcion_corta, :descripcion_completa, :servicios, :beneficios, :educacion, :email, :telefono, :website, :linkedin, :orden)";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':tipo' => $data['tipo'],
        ':nombre' => $data['nombre'],
        ':cargo' => $data['cargo'] ?? null,
        ':imagen' => $data['imagen'] ?? null,
        ':descripcion_corta' => $data['descripcion_corta'] ?? null,
        ':descripcion_completa' => $data['descripcion_completa'] ?? null,
        ':servicios' => isset($data['servicios']) ? json_encode($data['servicios']) : null,
        ':beneficios' => $data['beneficios'] ?? null,
        ':educacion' => $data['educacion'] ?? null,
        ':email' => $data['email'] ?? null,
        ':telefono' => $data['telefono'] ?? null,
        ':website' => $data['website'] ?? null,
        ':linkedin' => $data['linkedin'] ?? null,
        ':orden' => $data['orden'] ?? 0
    ]);

    echo json_encode(['success' => true, 'message' => 'Socio creado exitosamente']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear socio: ' . $e->getMessage()]);
}
?>
