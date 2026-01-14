<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Get all socios with empty type (or index 0 if enum failed hard)
    // In MySQL, invalid enum value becomes '' (index 0).
    $stmt = $pdo->query("SELECT id, cargo, beneficios FROM socios WHERE tipo = '' OR tipo IS NULL");
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($socios) . " socios with invalid type.\n";

    foreach ($socios as $socio) {
        $newType = 'institucional'; // Default

        if (!empty($socio['cargo'])) {
            $newType = 'natural';
        } elseif (!empty($socio['beneficios'])) {
            $newType = 'empresa';
        }

        $update = $pdo->prepare("UPDATE socios SET tipo = :tipo WHERE id = :id");
        $update->execute([':tipo' => $newType, ':id' => $socio['id']]);
        
        echo "Updated Socio ID {$socio['id']} to '$newType'\n";
    }

    echo "Done.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
