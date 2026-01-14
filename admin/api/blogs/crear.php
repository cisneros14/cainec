<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../config.php';

// Validar campos requeridos
if (empty($_POST['titulo']) || empty($_POST['slug']) || empty($_POST['id_categoria']) || empty($_POST['contenido'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Faltan campos obligatorios: título, slug, categoría y contenido'
    ]);
    exit;
}

$titulo = trim($_POST['titulo']);
$slug = trim($_POST['slug']);
$id_categoria = intval($_POST['id_categoria']);
$tipo = isset($_POST['tipo']) && in_array($_POST['tipo'], ['blog', 'noticia']) ? $_POST['tipo'] : 'blog';
$contenido = $_POST['contenido'];
$autor = isset($_POST['autor']) ? trim($_POST['autor']) : null;
$meta_descripcion = isset($_POST['meta_descripcion']) ? trim($_POST['meta_descripcion']) : null;
$meta_keywords = isset($_POST['meta_keywords']) ? trim($_POST['meta_keywords']) : null;
$etiquetas = isset($_POST['etiquetas']) ? trim($_POST['etiquetas']) : null;
$published_at = isset($_POST['published_at']) && !empty($_POST['published_at']) ? $_POST['published_at'] : null;

// Manejar subida de imagen
$portada_url = null;
if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    $file_type = $_FILES['portada']['type'];
    $file_size = $_FILES['portada']['size'];
    
    if (!in_array($file_type, $allowed_types)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de archivo no permitido. Solo JPG, PNG y WEBP'
        ]);
        exit;
    }
    
    if ($file_size > $max_size) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'El archivo es demasiado grande. Máximo 5MB'
        ]);
        exit;
    }
    
    // Generar nombre único
    $extension = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $upload_dir = __DIR__ . '/../../../assets/images/blog/portadas/';
    $upload_path = $upload_dir . $filename;
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    if (move_uploaded_file($_FILES['portada']['tmp_name'], $upload_path)) {
        $portada_url = 'assets/images/blog/portadas/' . $filename;
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al subir la imagen'
        ]);
        exit;
    }
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si el slug ya existe
    $stmt = $pdo->prepare("SELECT id FROM blogs WHERE slug = :slug");
    $stmt->execute(['slug' => $slug]);
    
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una entrada con ese slug'
        ]);
        exit;
    }
    
    // Insertar el blog
    $sql = "INSERT INTO blogs (
        id_categoria, tipo, titulo, slug, portada_url, meta_descripcion, 
        meta_keywords, etiquetas, contenido, autor, published_at, 
        created_at, updated_at
    ) VALUES (
        :id_categoria, :tipo, :titulo, :slug, :portada_url, :meta_descripcion,
        :meta_keywords, :etiquetas, :contenido, :autor, :published_at,
        NOW(), NOW()
    )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_categoria' => $id_categoria,
        'tipo' => $tipo,
        'titulo' => $titulo,
        'slug' => $slug,
        'portada_url' => $portada_url,
        'meta_descripcion' => $meta_descripcion,
        'meta_keywords' => $meta_keywords,
        'etiquetas' => $etiquetas,
        'contenido' => $contenido,
        'autor' => $autor,
        'published_at' => $published_at
    ]);
    
    $id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'message' => 'Entrada creada exitosamente',
        'data' => [
            'id' => $id,
            'titulo' => $titulo,
            'portada_url' => $portada_url
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear la entrada',
        'error' => $e->getMessage()
    ]);
}
