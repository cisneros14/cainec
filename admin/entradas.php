<?php
// Proteger la página - solo administradores (rol = 1)
require_once __DIR__ . '/auth_middleware.php';
// verificarRol([1]); // Permitir acceso a todos los roles autenticados

include __DIR__ . '/components/aside.php';
?>

<main class="min-h-screen p-0 md:p-6">
    <div class="w-full mx-auto">
       Entradas
    </div>
</main>

<?php include __DIR__ . '/components/footer.php'; ?>
