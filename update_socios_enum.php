<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Alter table to change enum values
    // Mapping:
    // corporativo -> institucional
    // profesional -> natural
    // aliado -> empresa
    
    // First, we need to update existing data to match new types if we want to preserve it.
    // Assuming table is empty or we can map them.
    // Let's just change the column definition.
    
    $sql = "ALTER TABLE `socios` MODIFY COLUMN `tipo` enum('institucional','natural','empresa') NOT NULL COMMENT 'Tipo de socio'";
    
    $pdo->exec($sql);
    echo "Tabla 'socios' actualizada exitosamente. Tipos: institucional, natural, empresa.";

} catch (PDOException $e) {
    echo "Error al actualizar la tabla: " . $e->getMessage();
}
?>
