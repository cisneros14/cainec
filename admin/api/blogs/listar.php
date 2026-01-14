<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Consultar blogs con JOIN a categorías
    $stmt = $pdo->query("
        SELECT 
            b.*,
            c.nombre as categoria_nombre
        FROM blogs b
        LEFT JOIN categoria_blog c ON b.id_categoria = c.id
        ORDER BY b.created_at DESC
    ");
    
    $blogs = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => $blogs,
        'count' => count($blogs)
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener las entradas',
        'error' => $e->getMessage()
    ]);
}
