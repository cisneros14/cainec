<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../../config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos no válidos o ID faltante']);
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si se subió una nueva imagen, eliminar la anterior
    if (isset($data['url_img']) && !empty($data['url_img'])) {
        $stmt = $pdo->prepare("SELECT url_img FROM embajadores WHERE id = :id");
        $stmt->execute([':id' => $data['id']]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($current && !empty($current['url_img']) && $current['url_img'] !== $data['url_img']) {
            $oldImagePath = __DIR__ . '/../../../' . $current['url_img'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    }

    $sql = "UPDATE embajadores SET 
            nombre = :nombre,
            apellido = :apellido,
            categoria = :categoria,
            descripcion = :descripcion,
            url_img = :url_img,
            telefono = :telefono,
            correo = :correo,
            orden = :orden
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nombre' => $data['nombre'],
        ':apellido' => $data['apellido'],
        ':categoria' => $data['categoria'] ?? null,
        ':descripcion' => $data['descripcion'] ?? null,
        ':url_img' => $data['url_img'] ?? null,
        ':telefono' => $data['telefono'] ?? null,
        ':correo' => $data['correo'],
        ':orden' => $data['orden'] ?? 0,
        ':id' => $data['id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Embajador actualizado exitosamente'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar el embajador',
        'error' => $e->getMessage()
    ]);
}
?>