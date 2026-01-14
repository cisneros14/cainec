<?php
session_start();

// Si viene con parámetro ?logout=1, destruir la sesión
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
    session_start(); // Iniciar nueva sesión limpia
}

// Si ya está logueado, redirigir según rol
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 2) {
        header('Location: admin/entradasSocios.php');
    } else {
        header('Location: admin/entradas.php');
    }
    exit;
}

// Procesar login
$error = '';
$success = '';
$info = '';

// Verificar mensajes en la URL
if (isset($_GET['success'])) {
    $success = htmlspecialchars($_GET['success']);
} elseif (isset($_GET['info'])) {
    $info = htmlspecialchars($_GET['info']);
} elseif (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $info = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.';
} elseif (isset($_GET['unauthorized'])) {
    $error = 'Debes iniciar sesión para acceder a esa página.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    require_once 'config.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['rememberme']);
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor, ingresa tu usuario y contraseña';
    } else {
        try {
            // Crear conexión PDO
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Buscar usuario por username o email (sin filtro de estado aquí)
            $stmt = $pdo->prepare("
                SELECT id, nombre, apellido, usuario, email, password_hash, rol, cargo, estado, img_url 
                FROM usuarios 
                WHERE (usuario = ? OR email = ?)
                LIMIT 1
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Verificar estado del usuario
                if ($user['estado'] == 0) {
                    // Usuario pendiente de aprobación
                    $info = 'Su usuario está registrado pero se encuentra en estado de revisión. Se le notificará por correo electrónico cuando su cuenta sea aprobada por un administrador.';
                } else {
                    // Login exitoso - usuario activo
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nombre'] = $user['nombre'];
                    $_SESSION['user_apellido'] = $user['apellido'];
                    $_SESSION['user_usuario'] = $user['usuario'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_rol'] = $user['rol'];
                    $_SESSION['user_cargo'] = $user['cargo'];
                    $_SESSION['user_img'] = $user['img_url'];
                    $_SESSION['login_time'] = time();
                    
                    // Si marcó "recordarme", extender la sesión
                    if ($remember) {
                        $_SESSION['remember_me'] = true;
                        ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 días
                        session_regenerate_id(true);
                    }
                    
                    // Redirigir según el rol
                    if ($user['rol'] == 2) {
                        // Rol 2: Socio - redirigir a entradasSocios.php
                        $success = 'Inicio de sesión exitoso. Redirigiendo...';
                        header('Refresh: 1; URL=admin/entradasSocios.php');
                    } else {
                        // Otros roles - redirigir a entradas.php
                        $success = 'Inicio de sesión exitoso. Redirigiendo...';
                        header('Refresh: 1; URL=admin/entradas.php');
                    }
                }
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
        } catch (PDOException $e) {
            $error = 'Error de conexión. Por favor, intenta más tarde.';
        }
    }
}
?>
<?php include 'components/header.php'; ?>
<script>
    // togglePasswordVisibility recibe el botón que se pulsó (this) y alterna el tipo del input
    function togglePasswordVisibility(btn) {
        if (!btn) return;
        // obtener el id del input referenciado por aria-describedby o buscar el input dentro del contenedor
        const described = btn.getAttribute('aria-describedby');
        const input = described ? document.getElementById(described) : btn.closest('.relative')?.querySelector('input');
        if (!input) return;
        const svg = btn.querySelector('svg');

        if (input.type === 'password') {
            input.type = 'text';
            // icono "eye-off" (oculto -> visible)
            if (svg) svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223a10.477 10.477 0 00-1.942 3.754 1.012 1.012 0 000 .646C3.423 16.49 7.3 19.5 12 19.5c1.874 0 3.63-.5 5.147-1.384M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8" />';
        } else {
            input.type = 'password';
            // icono "eye" (visible -> oculto)
            if (svg) svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />\n                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        }
    }
</script>
<!-- end: Header Area -->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">
            <div class="space-for-header"></div>
            <!-- start: Login Section -->
            <section class="full-width tj-page__area section-gap !pb-0">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="tj-page__container">
                                <div class="tj-entry__content">
                                    <div class="flex flex-col w-100">
                                        <div class="max-w-[500px] w-full mx-auto">
                                            <h3>Iniciar sesión</h3>
                                            
                                            <?php if (!empty($error)): ?>
                                            <div class="alert alert-error mb-4 p-3 md:p-4 rounded-lg !bg-red-100 border !border-red-200 flex items-start gap-3">
                                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium mb-0 text-red-800"><?= htmlspecialchars($error) ?></p>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($success)): ?>
                                            <div class="alert alert-success mb-4 p-3 md:p-4 rounded-lg !bg-green-100 border !border-green-200 flex items-start gap-3">
                                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium mb-0 text-green-800"><?= htmlspecialchars($success) ?></p>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($info)): ?>
                                            <div class="alert alert-info mb-4 p-3 md:p-4 rounded-lg !bg-yellow-100 border !border-yellow-200 flex items-start gap-3">
                                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium mb-0 text-yellow-800"><?= htmlspecialchars($info) ?></p>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <form class="woocommerce-form woocommerce-form-login login" method="post"
                                                novalidate="">
                                                <div class="flex flex-col space-y-2 !mb-3">
                                                    <label for="username"
                                                        class="text-sm font-medium text-gray-700 !mb-3">
                                                        Nombre de usuario o correo electrónico <span
                                                            class="text-red-500">*</span>
                                                    </label>

                                                    <input type="text" name="username" id="username"
                                                        autocomplete="username" required aria-required="true"
                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 "
                                                        placeholder="ejemplo@correo.com" />
                                                </div>

                                                <div class="flex flex-col space-y-2 !mb-3">
                                                    <label for="password" class="text-sm font-medium text-gray-700 !mb-3">
                                                        Contraseña <span class="text-red-500">*</span>
                                                    </label>

                                                    <div class="relative">
                                                        <input type="password" name="password" id="password"
                                                            autocomplete="current-password" required
                                                            aria-required="true"
                                                            class="w-full !bg-white rounded-xl border border-gray-300 px-4 py-2 pr-10 text-gray-800 placeholder-gray-400 "
                                                            placeholder="••••••••" />
                                                        <button type="button"
                                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                                            aria-label="Mostrar contraseña" aria-describedby="password"
                                                            onclick="togglePasswordVisibility(this)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>


                                                <div class="row form-row algin-items-center rg-15">
                                                    <div class="col-6">
                                                        <label
                                                            class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                                            <input
                                                                class="woocommerce-form__input woocommerce-form__input-checkbox"
                                                                name="rememberme" type="checkbox" id="rememberme"
                                                                value="forever"> <span>Recordarme</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <p class="woocommerce-LostPassword lost_password">
                                                            <a href="password.php">¿Olvidaste tu contraseña?</a>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="submit"
                                                            class="!bg-[var(--tj-color-theme-primary)] w-full px-4 py-2 !rounded-xl text-white"
                                                            name="login" value="Log in">
                                                            <span class="btn-text"><span>Iniciar sesión</span></span>
                                                        </button>
                                                    </div>
                                                </div>


                                            </form>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Login Section -->



                   <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>