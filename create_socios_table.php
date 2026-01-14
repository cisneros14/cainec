<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS `socios` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tipo` enum('corporativo','profesional','aliado') NOT NULL COMMENT 'Tipo de socio',
      `nombre` varchar(255) NOT NULL,
      `cargo` varchar(255) DEFAULT NULL COMMENT 'Solo para profesionales',
      `imagen` varchar(255) DEFAULT NULL COMMENT 'URL de imagen (profesionales) o clase de icono (corporativos/aliados)',
      `descripcion_corta` text,
      `descripcion_completa` text,
      `servicios` json DEFAULT NULL COMMENT 'Array de servicios o especialidades',
      `beneficios` text DEFAULT NULL COMMENT 'Solo para aliados',
      `educacion` varchar(255) DEFAULT NULL COMMENT 'Solo para profesionales',
      `email` varchar(255) DEFAULT NULL,
      `telefono` varchar(50) DEFAULT NULL,
      `website` varchar(255) DEFAULT NULL,
      `linkedin` varchar(255) DEFAULT NULL,
      `orden` int(11) DEFAULT 0,
      `estado` tinyint(1) DEFAULT 1,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Tabla 'socios' creada exitosamente.";

} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
?>
