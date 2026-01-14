<?php
require_once 'config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $columns = [
        "ADD COLUMN `nombre_juridico` varchar(255) DEFAULT NULL COMMENT 'Nombre jurídico (organizaciones y empresas)'",
        "ADD COLUMN `sector` varchar(255) DEFAULT NULL COMMENT 'Sector que representa (organizaciones)'",
        "ADD COLUMN `representante_legal` varchar(255) DEFAULT NULL COMMENT 'Representante legal'",
        "ADD COLUMN `director_ejecutivo` varchar(255) DEFAULT NULL COMMENT 'Director ejecutivo o asistente'",
        "ADD COLUMN `inicio_actividades` date DEFAULT NULL COMMENT 'Fecha de inicio de actividades'",
        "ADD COLUMN `numero_miembros` int(11) DEFAULT NULL COMMENT 'Número de miembros o socios'",
        "ADD COLUMN `logo_url` varchar(255) DEFAULT NULL COMMENT 'Logotipo de la organización o empresa'",
        "ADD COLUMN `registro_profesional` varchar(100) DEFAULT NULL COMMENT 'Número de registro profesional'",
        "ADD COLUMN `actividad` varchar(255) DEFAULT NULL COMMENT 'Actividad principal'",
        "ADD COLUMN `ciudad_operaciones` varchar(100) DEFAULT NULL COMMENT 'Ciudad donde opera principalmente'",
        "ADD COLUMN `nivel_educacion` varchar(100) DEFAULT NULL COMMENT 'Nivel de educación'",
        "ADD COLUMN `genero` varchar(50) DEFAULT NULL COMMENT 'Género'",
        "ADD COLUMN `fecha_nacimiento` date DEFAULT NULL COMMENT 'Fecha de nacimiento'",
        "ADD COLUMN `plazas_trabajo_generadas` int(11) DEFAULT NULL COMMENT 'Número de plazas de trabajo generadas'"
    ];

    foreach ($columns as $col) {
        try {
            $sql = "ALTER TABLE `usuarios` $col";
            $pdo->exec($sql);
            echo "Executed: $sql <br>";
        } catch (PDOException $e) {
            // Ignore "Duplicate column name" errors
            if (strpos($e->getMessage(), "Duplicate column name") !== false) {
                echo "Skipped (already exists): $col <br>";
            } else {
                echo "Error executing: $col - " . $e->getMessage() . "<br>";
            }
        }
    }

    echo "Database schema update completed.";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>