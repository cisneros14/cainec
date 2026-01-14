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

    // Consultar todos los miembros de la junta directiva ordenados por orden ascendente
    $stmt = $pdo->query("
        SELECT id, nombre, apellido, categoria, rol, descripcion, url_img, telefono, correo, created_at, updated_at 
        FROM junta_directiva 
        ORDER BY orden ASC, created_at DESC
    ");

    $miembros = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $miembros,
        'count' => count($miembros)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los miembros de la junta directiva',
        'error' => $e->getMessage()
    ]);
}
