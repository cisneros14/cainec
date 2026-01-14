<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM socios WHERE estado = 1 ORDER BY orden ASC, id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Decodificar JSON de servicios
    foreach ($socios as &$socio) {
        if ($socio['servicios']) {
            $socio['servicios'] = json_decode($socio['servicios']);
        } else {
            $socio['servicios'] = [];
        }
    }

    echo json_encode(['success' => true, 'data' => $socios]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener socios: ' . $e->getMessage()]);
}
?>
