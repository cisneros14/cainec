<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir configuración de base de datos
require_once __DIR__ . '/../../../config.php';

try {
    // Crear conexión PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Consultar todas las categorías ordenadas por nombre
    $stmt = $pdo->query("
        SELECT id, nombre 
        FROM categorias_junta 
        ORDER BY nombre ASC
    ");

    $categorias = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $categorias,
        'count' => count($categorias)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener las categorías',
        'error' => $e->getMessage()
    ]);
}
