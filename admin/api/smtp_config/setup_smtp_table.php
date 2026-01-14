<?php
require_once __DIR__ . '/../../../config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS smtp_config (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,

        smtp_host VARCHAR(255) NOT NULL,
        smtp_port INT NOT NULL DEFAULT 587,
        smtp_username VARCHAR(255) NOT NULL,
        smtp_password VARCHAR(255) NOT NULL,
        encryption ENUM('tls','ssl','none') DEFAULT 'tls',

        from_name VARCHAR(255),
        from_email VARCHAR(255) NOT NULL,

        is_default TINYINT(1) DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,

        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        CONSTRAINT fk_smtp_usuario
            FOREIGN KEY (user_id)
            REFERENCES usuarios(id)
            ON DELETE CASCADE,

        UNIQUE KEY unique_user_email (user_id, from_email),
        INDEX idx_user_id (user_id),
        INDEX idx_is_default (is_default),
        INDEX idx_is_active (is_active)
    ) ENGINE=InnoDB;";

    $pdo->exec($sql);
    echo "Table 'smtp_config' created successfully or already exists.\n";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
?>
