<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar configuración global (define URL_APP)
require_once __DIR__ . '/../config.php';

// Crear conexión PDO si no existe
if (!isset($pdo)) {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    } catch (PDOException $e) {
        // Si falla la conexión, simplemente no mostrar el dropdown
        $pdo = null;
    }
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay usuario autenticado
$usuarioAutenticado = false;
$datosUsuario = null;

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $pdo !== null) {
    $usuarioAutenticado = true;

    // Obtener datos del usuario de la base de datos
    try {
        $stmt = $pdo->prepare("SELECT id, nombre, apellido, img_url, usuario, email FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el usuario, cerrar sesión
        if (!$datosUsuario) {
            $usuarioAutenticado = false;
            session_unset();
            session_destroy();
        }
    } catch (PDOException $e) {
        $usuarioAutenticado = false;
    }
}

// Función para obtener la URL de la imagen del usuario
function obtenerImagenUsuario($datosUsuario)
{
    if (!empty($datosUsuario['img_url'])) {
        return htmlspecialchars($datosUsuario['img_url']);
    }

    // Generar avatar con iniciales usando UI Avatars
    $nombre = $datosUsuario['nombre'] ?? '';
    $apellido = $datosUsuario['apellido'] ?? '';
    $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1));

    // Si no hay iniciales, usar ícono de usuario
    if (empty($iniciales)) {
        $iniciales = 'U';
    }

    // Usar placeholder local si no hay foto del usuario
    return 'assets/images/recursos/placeholder.webp';
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <!-- Site Title -->
    <title>CAINEC - Cámara Inmobiliaria Ecuatoriana</title>

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fav.png">

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome-pro.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/bexon-icons.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/venobox.min.css">
    <link rel="stylesheet" href="assets/css/odometer-theme-default.css">
    <link rel="stylesheet" href="assets/css/meanmenu.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="body-overlay"></div>

    <!-- Preloader Start -->
    <!-- <div class="tj-preloader is-loading">
        <div class="tj-preloader-inner">
            <div class="tj-preloader-ball-wrap">
                <div class="tj-preloader-ball-inner-wrap">
                    <div class="tj-preloader-ball-inner">
                        <div class="tj-preloader-ball"></div>
                    </div>
                    <div class="tj-preloader-ball-shadow"></div>
                </div>
                <div id="tj-weave-anim" class="tj-preloader-text">Loading...</div>
            </div>
        </div>
        <div class="tj-preloader-overlay"></div>
    </div> -->
    <!-- Preloader end -->

    <!-- back to top start -->
    <div id="tj-back-to-top"><span id="tj-back-to-top-percentage"></span></div>
    <!-- back to top end -->

    <!-- start: Search Popup -->
    <div class="search-popup-overlay"></div>
    <!-- end: Search Popup -->

    <!-- start: Offcanvas Menu -->
    <div class="tj-offcanvas-area d-none d-lg-block">
        <div class="hamburger_bg"></div>
        <div class="hamburger_wrapper">
            <div class="hamburger_inner">
                <div class="hamburger_top d-flex align-items-center justify-content-between">
                    <div class="hamburger_logo">
                        <a href="index.php" class="mobile_logo">
                            <img src="assets/images/logos/logo.webp" class="px-3 py-1 bg-[#0d2b58] rounded-lg"
                                alt="Logo">
                        </a>
                    </div>
                    <div class="hamburger_close">
                        <button class="hamburger_close_btn"><i class="fa-thin fa-times"></i></button>
                    </div>
                </div>
                <div class="offcanvas-text">
                    <p>CAINEC es la organización que integra a profesionales y empresas del sector inmobiliario,
                        promoviendo la formalización, la ética y el desarrollo competitivo de la industria en Ecuador.
                    </p>
                </div>
                <div class="hamburger-search-area">
                    <h5 class="hamburger-title">Buscar</h5>
                    <div class="hamburger_search">
                        <form method="get" action="index.php">
                            <button type="submit"><i class="tji-search"></i></button>
                            <input type="search" autocomplete="off" name="s" value="" placeholder="Buscar...">
                        </form>
                    </div>
                </div>
                <div class="hamburger-infos">
                    <h5 class="hamburger-title">Información de Contacto</h5>
                    <div class="contact-info">
                        <div class="contact-item">
                            <span class="subtitle">Teléfono</span>
                            <a class="contact-link" href="tel:+59342123456">+593 4-212-3456</a>
                        </div>
                        <div class="contact-item">
                            <span class="subtitle">Email</span>
                            <a class="contact-link" href="mailto:info@cainec.com">info@cainec.com</a>
                        </div>
                        <div class="contact-item">
                            <span class="subtitle">Ubicación</span>
                            <span class="contact-link">Cuenca, Ecuador</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hamburger-socials">
                <h5 class="hamburger-title">Síguenos</h5>
                <div class="social-links style-3">
                    <ul>
                        <li><a href="https://www.facebook.com/" target="_blank"><i
                                    class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li><a href="https://www.instagram.com/" target="_blank"><i
                                    class="fa-brands fa-instagram"></i></a>
                        </li>
                        <li><a href="https://x.com/" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                        <li><a href="https://www.linkedin.com/" target="_blank"><i
                                    class="fa-brands fa-linkedin-in"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end: Offcanvas Menu -->

    <!-- start: Hamburger Menu -->
    <div class="hamburger-area d-lg-none">
        <div class="hamburger_bg"></div>
        <div class="hamburger_wrapper">
            <div class="hamburger_inner">
                <div class="hamburger_top d-flex align-items-center justify-content-between">
                    <div class="hamburger_logo">
                        <a href="index.php" class="mobile_logo">
                            <img src="assets/images/logos/logo.webp" class="px-3 py-1 bg-[#0d2b58] rounded-lg"
                                alt="Logo">
                        </a>
                    </div>
                    <div class="hamburger_close">
                        <button class="hamburger_close_btn"><i class="fa-thin fa-times"></i></button>
                    </div>
                </div>
                <div class="hamburger_menu">
                    <div class="mobile_menu"></div>
                </div>
                <div class="hamburger-infos">
                    <h5 class="hamburger-title">Información de Contacto</h5>
                    <div class="contact-info">
                        <div class="contact-item">
                            <span class="subtitle">Teléfono</span>
                            <a class="contact-link" href="tel:+59342123456">+593 4-212-3456</a>
                        </div>
                        <div class="contact-item">
                            <span class="subtitle">Email</span>
                            <a class="contact-link" href="mailto:info@cainec.com">info@cainec.com</a>
                        </div>
                        <div class="contact-item">
                            <span class="subtitle">Ubicación</span>
                            <span class="contact-link">Cuenca, Ecuador</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hamburger-socials">
                <h5 class="hamburger-title">Síguenos</h5>
                <div class="social-links style-3">
                    <ul>
                        <li><a href="https://www.facebook.com/" target="_blank"><i
                                    class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li><a href="https://www.instagram.com/" target="_blank"><i
                                    class="fa-brands fa-instagram"></i></a>
                        </li>
                        <li><a href="https://x.com/" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                        <li><a href="https://www.linkedin.com/" target="_blank"><i
                                    class="fa-brands fa-linkedin-in"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end: Hamburger Menu -->

    <!-- start: Header Area -->
    <header class="header-area header-1 header-absolute  section-gap-x">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="header-wrapper">
                        <!-- site logo -->
                        <div class="site_logo">
                            <a class="logo px-2 !bg-[var(--tj-color-theme-primary)] rounded-lg overflow-hidden"
                                href="index.php"><img src="assets/images/logos/logo.webp" alt=""></a>
                        </div>

                        <!-- navigation -->
                        <div class="menu-area d-none d-lg-inline-flex align-items-center">
                            <nav id="mobile-menu" class="mainmenu">
                                <ul>
                                    <li><a href="index.php">Inicio</a></li>

                                    <li class="has-dropdown"><a href="about.php">Acerca de</a>
                                        <ul class="sub-menu header__mega-menu mega-menu mega-menu-pages !w-fit">
                                            <li class="!w-fit">
                                                <div class="!w-fit">

                                                    <div class="mega-menu-pages-single">
                                                        <div class="mega-menu-pages-single-inner">
                                                            <div class="mega-menu-list">
                                                                <a href="about.php">Nosotros</a>
                                                                <a href="junta.php">Miembros Cainec</a>
                                                                <a href="junta-directiva.php">Junta Directiva</a>

                                                                <a href="embajadores.php">Embajadores Cainec</a>
                                                                <a href="reconocimiento-socios.php">Reconocimiento
                                                                    Socios</a>
                                                                <a href="insignias-apolo.php">Modesto Gerardo Apolo</a>

                                                                <a href="team.php">Alianzas</a>
                                                                <a href="news.php">Ultimas Noticias</a>
                                                                <a href="blog-right-sidebar.php">Blog</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="inmotrends.php">Congreso Inmotrends</a></li>

                                    <!-- <li class="has-dropdown"><a href="service.html">Servicios</a>
                                        <ul class="sub-menu  mega-menu-service">
                                            <li> <a href="service-details.html">Capacitaciónes</a></li>
                                            <li> <a href="service-details.html">Alianzas Nacionales</a></li>
                                            <li> <a href="service-details.html">Alianzas Internacionales</a></li>
                                        </ul>
                                    </li> -->

                                    <li><a href="contact.php">Contacto</a></li>
                                    <li><a href="https://mercadoinmobiliario.ec/listing.php"
                                            target="_blank">Propiedades</a></li>
                                </ul>
                            </nav>
                        </div>

                        <!-- header right info -->
                        <div class="header-right-item d-none d-lg-inline-flex">
                            <div class="header-search">
                                <button class="search">
                                    <i class="tji-search"></i>
                                </button>
                                <button type="button" class="search_close_btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <?php if ($usuarioAutenticado && $datosUsuario): ?>
                                <!-- Dropdown Usuario Autenticado -->
                                <div class="header-user-dropdown relative">
                                    <button
                                        class="user-dropdown-trigger flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                        <img src="<?php echo obtenerImagenUsuario($datosUsuario); ?>"
                                            alt="<?php echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']); ?>"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-[var(--tj-color-theme-primary)]">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </button>

                                    <div
                                        class="user-dropdown-menu absolute right-0 top-full mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transition-all duration-200 z-50">
                                        <div class="p-3 border-b border-gray-200">
                                            <div class="flex items-start gap-3">
                                                <img src="<?php echo obtenerImagenUsuario($datosUsuario); ?>"
                                                    alt="<?php echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']); ?>"
                                                    class="w-12 h-12 rounded-full object-cover">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 truncate mb-0">
                                                        <?php echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']); ?>
                                                    </p>
                                                    <p class="text-sm text-gray-600 truncate mb-0">
                                                        <?php echo htmlspecialchars($datosUsuario['email']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2">
                                            <a href="admin/perfil.php"
                                                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-50 transition-colors text-gray-700">
                                                <i class="fa-solid fa-user w-5"></i>
                                                <span>Mi Perfil</span>
                                            </a>
                                            <a href="login.php?logout=1"
                                                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-red-50 transition-colors text-red-600">
                                                <i class="fa-solid fa-right-from-bracket w-5"></i>
                                                <span>Cerrar Sesión</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Botón Login para usuarios no autenticados -->
                                <div class="header-button">
                                    <a class="tj-primary-btn" href="login.php">
                                        <span class="btn-text"><span>Acceso Socios</span></span>
                                        <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="menu_bar menu_offcanvas d-none d-lg-inline-flex">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>

                        <!-- menu bar -->
                        <div class="menu_bar mobile_menu_bar d-lg-none">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Popup -->
        <div class="search_popup">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="tj_search_wrapper">
                            <div class="search_form">
                                <form action="#">
                                    <div class="search_input">
                                        <div class="search-box">
                                            <input class="search-form-input" type="text"
                                                placeholder="Escribe y presiona Enter" required>
                                            <button type="submit">
                                                <i class="tji-search"></i>
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
    </header>
    <!-- end: Header Area -->

    <!-- Script para el dropdown del usuario (con debug en consola) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                console.debug('[dropdown] DOMContentLoaded: inicializando dropdowns de usuario');

                // Obtener todos los dropdowns
                const dropdowns = document.querySelectorAll('.header-user-dropdown');
                console.debug('[dropdown] cantidad de .header-user-dropdown encontradas:', dropdowns.length);

                dropdowns.forEach((dropdown, index) => {
                    const trigger = dropdown.querySelector('.user-dropdown-trigger');
                    const menu = dropdown.querySelector('.user-dropdown-menu');
                    console.debug('[dropdown] procesando dropdown', index, { triggerExists: !!trigger, menuExists: !!menu });

                    if (!trigger || !menu) return;

                    // Asegurar estado inicial
                    menu.style.setProperty('display', 'none', 'important');
                    menu.style.setProperty('opacity', '0', 'important');
                    menu.style.setProperty('visibility', 'hidden', 'important');
                    trigger.setAttribute('aria-expanded', 'false');

                    // Toggle del dropdown al hacer click en el botón
                    trigger.addEventListener('click', function (e) {
                        try {
                            e.preventDefault();
                            e.stopPropagation();
                            console.debug('[dropdown] click en trigger (index ' + index + ')');

                            // Cerrar otros dropdowns abiertos
                            document.querySelectorAll('.user-dropdown-menu').forEach(otherMenu => {
                                if (otherMenu !== menu) {
                                    otherMenu.style.setProperty('display', 'none', 'important');
                                    otherMenu.style.setProperty('opacity', '0', 'important');
                                    otherMenu.style.setProperty('visibility', 'hidden', 'important');
                                }
                            });

                            // Toggle del dropdown actual
                            const computed = window.getComputedStyle(menu);
                            const currentlyVisible = computed.display !== 'none' && computed.visibility === 'visible' && parseFloat(computed.opacity) > 0;
                            console.debug('[dropdown] estado actual del menu:', { display: computed.display, visibility: computed.visibility, opacity: computed.opacity, currentlyVisible });

                            if (currentlyVisible) {
                                // Cerrar
                                menu.style.setProperty('opacity', '0', 'important');
                                menu.style.setProperty('visibility', 'hidden', 'important');
                                // Usar display none inmediatamente para evitar interacción
                                menu.style.setProperty('display', 'none', 'important');
                                trigger.setAttribute('aria-expanded', 'false');
                                console.debug('[dropdown] cerrado (index ' + index + ')');
                            } else {
                                // Abrir
                                menu.style.setProperty('display', 'block', 'important');
                                // Forzar reflow para transiciones si aplica
                                // eslint-disable-next-line no-unused-expressions
                                menu.offsetHeight;
                                menu.style.setProperty('opacity', '1', 'important');
                                menu.style.setProperty('visibility', 'visible', 'important');
                                trigger.setAttribute('aria-expanded', 'true');
                                console.debug('[dropdown] abierto (index ' + index + ')');
                            }
                        } catch (err) {
                            console.error('[dropdown] error en click handler:', err);
                        }
                    });
                });

                // Cerrar dropdown al hacer click fuera
                document.addEventListener('click', function (e) {
                    try {
                        if (!e.target.closest('.header-user-dropdown')) {
                            document.querySelectorAll('.user-dropdown-menu').forEach(menu => {
                                menu.style.setProperty('display', 'none', 'important');
                                menu.style.setProperty('opacity', '0', 'important');
                                menu.style.setProperty('visibility', 'hidden', 'important');
                            });
                            console.debug('[dropdown] click fuera, cerrando menus');
                        }
                    } catch (err) {
                        console.error('[dropdown] error en document click handler:', err);
                    }
                });

                // Cerrar dropdown con tecla Escape
                document.addEventListener('keydown', function (e) {
                    try {
                        if (e.key === 'Escape') {
                            document.querySelectorAll('.user-dropdown-menu').forEach(menu => {
                                menu.style.setProperty('display', 'none', 'important');
                                menu.style.setProperty('opacity', '0', 'important');
                                menu.style.setProperty('visibility', 'hidden', 'important');
                            });
                            console.debug('[dropdown] Escape presionado, cerrando menus');
                        }
                    } catch (err) {
                        console.error('[dropdown] error en keydown handler:', err);
                    }
                });

            } catch (e) {
                console.error('[dropdown] error inicializando dropdowns:', e);
            }
        });
    </script>

    <!-- start: Header Area -->
    <header class="header-area header-1 header-duplicate header-sticky  section-gap-x">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="header-wrapper">
                        <!-- site logo -->
                        <div class="site_logo">
                            <a class="logo px-2 !bg-[var(--tj-color-theme-primary)] rounded-lg overflow-hidden"
                                href="index.php"><img src="assets/images/logos/logo.webp" alt=""></a>
                        </div>

                        <!-- navigation -->
                        <div class="menu-area d-none d-lg-inline-flex align-items-center">
                            <nav class="mainmenu">
                                <ul>
                                    <li><a href="index.php">Inicio</a></li>

                                    <li class="has-dropdown"><a href="about.php">Acerca de</a>
                                        <ul class="sub-menu header__mega-menu mega-menu mega-menu-pages !w-fit">
                                            <li class="!w-fit">
                                                <div class="!w-fit">

                                                    <div class="mega-menu-pages-single">
                                                        <div class="mega-menu-pages-single-inner">
                                                            <div class="mega-menu-list">
                                                                <a href="about.php">Nosotros</a>
                                                                <a href="junta.php">Miembros Cainec</a>
                                                                <a href="junta-directiva.php">Junta Directiva</a>

                                                                <a href="embajadores.php">Embajadores Cainec</a>
                                                                <a href="reconocimiento-socios.php">Reconocimiento
                                                                    Socios</a>
                                                                <a href="insignias-apolo.php">Modesto Gerardo Apolo</a>

                                                                <a href="team.php">Alianzas</a>
                                                                <a href="news.php">Ultimas Noticias</a>
                                                                <a href="blog-right-sidebar.php">Blog</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="inmotrends.php">Congreso Inmotrends</a></li>
                                    <!-- <li class="has-dropdown"><a href="service.html">Servicios</a>
                                        <ul class="sub-menu  mega-menu-service">
                                            <li> <a href="service-details.html">Capacitaciónes</a></li>
                                            <li> <a href="service-details.html">Alianzas Nacionales</a></li>
                                            <li> <a href="service-details.html">Alianzas Internacionales</a></li>
                                        </ul>
                                    </li> -->

                                    <li><a href="contact.php">Contacto</a></li>
                                    <li><a href="https://mercadoinmobiliario.ec/listing.php"
                                            target="_blank">Propiedades</a></li>
                                </ul>
                            </nav>
                        </div>

                        <!-- header right info -->
                        <div class="header-right-item d-none d-lg-inline-flex">
                            <div class="header-search">
                                <button class="search">
                                    <i class="tji-search"></i>
                                </button>
                                <button type="button" class="search_close_btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <?php if ($usuarioAutenticado && $datosUsuario): ?>
                                <!-- Dropdown Usuario Autenticado -->
                                <div class="header-user-dropdown relative">
                                    <button
                                        class="user-dropdown-trigger flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                        <img src="<?php echo obtenerImagenUsuario($datosUsuario); ?>"
                                            alt="<?php echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']); ?>"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-[var(--tj-color-theme-primary)]">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </button>

                                    <div
                                        class="user-dropdown-menu absolute right-0 top-full mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transition-all duration-200 z-50">
                                        <div class="p-3 border-b border-gray-200">
                                            <div class="flex items-start gap-3">
                                                <img src="<?php echo obtenerImagenUsuario($datosUsuario); ?>"
                                                    alt="<?php echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']); ?>"
                                                    class="w-12 h-12 rounded-full object-cover">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 truncate mb-0">
                                                        <?php echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']); ?>
                                                    </p>
                                                    <p class="text-sm text-gray-600 truncate mb-0">
                                                        <?php echo htmlspecialchars($datosUsuario['email']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2">
                                            <a href="admin/perfil.php"
                                                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-50 transition-colors text-gray-700">
                                                <i class="fa-solid fa-user w-5"></i>
                                                <span>Mi Perfil</span>
                                            </a>
                                            <a href="login.php?logout=1"
                                                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-red-50 transition-colors text-red-600">
                                                <i class="fa-solid fa-right-from-bracket w-5"></i>
                                                <span>Cerrar Sesión</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Botón Login para usuarios no autenticados -->
                                <div class="header-button">
                                    <a class="tj-primary-btn" href="login.php">
                                        <span class="btn-text"><span>Acceso Socios</span></span>
                                        <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="menu_bar menu_offcanvas d-none d-lg-inline-flex">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>

                        <!-- menu bar -->
                        <div class="menu_bar mobile_menu_bar d-lg-none">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Popup -->
        <div class="search_popup">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="tj_search_wrapper">
                            <div class="search_form">
                                <form action="#">
                                    <div class="search_input">
                                        <div class="search-box">
                                            <input class="search-form-input" type="text"
                                                placeholder="Escribe y presiona Enter" required>
                                            <button type="submit">
                                                <i class="tji-search"></i>
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
    </header>