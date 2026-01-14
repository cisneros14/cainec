<?php
require_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Add 'orden' column if it doesn't exist
    $sql = "ALTER TABLE socios_apolo ADD COLUMN orden INT DEFAULT 0";
    $pdo->exec($sql);

    echo "Columna 'orden' agregada exitosamente.\n";

} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "La columna 'orden' ya existe.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>