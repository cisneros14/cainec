<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $pdo->query("
        SELECT * 
        FROM socios_apolo 
        ORDER BY orden ASC, created_at DESC
    ");

    $socios = $stmt->fetchAll();

    // Decodificar logros para que sean array en el JSON
    foreach ($socios as &$socio) {
        $socio['logros'] = json_decode($socio['logros'], true);
    }

    echo json_encode([
        'success' => true,
        'data' => $socios,
        'count' => count($socios)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los socios',
        'error' => $e->getMessage()
    ]);
}
?>