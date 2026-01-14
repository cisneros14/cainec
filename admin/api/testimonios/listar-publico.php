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
    
    // Consultar todos los testimonios ordenados por fecha de creación descendente
    $stmt = $pdo->query("
        SELECT id, foto_url, nombre, cargo, testimonio, fecha_creacion 
        FROM testimonios 
        ORDER BY fecha_creacion DESC
    ");
    
    $testimonios = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => $testimonios,
        'count' => count($testimonios)
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los testimonios',
        'error' => $e->getMessage()
    ]);
}
