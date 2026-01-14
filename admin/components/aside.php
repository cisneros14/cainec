<?php
// Cargar configuración global (define URL_APP)
// __DIR__ apunta a .../admin/components, por eso subimos 2 niveles hasta la raíz del proyecto
require_once __DIR__ . '/../../config.php';
// detectar archivo actual para marcar enlace activo
$currentFile = basename($_SERVER['PHP_SELF'] ?? '');

// --- VARIABLES DE NAVEGACIÓN GENERAL ---
$isEntradas = ($currentFile === 'entradas.php');
$isUsuarios = ($currentFile === 'usuarios.php');
$isSolicitudes = ($currentFile === 'solicitudes.php');
$isCorreo = ($currentFile === 'smtp_config.php');
$isBlogs = ($currentFile === 'blogs.php');
$isTestimonios = ($currentFile === 'testimonios.php');
$isCategoriaBlogs = ($currentFile === 'categoriaBlogs.php');
$isPerfil = ($currentFile === 'perfil.php');
$isJuntaDirectiva = ($currentFile === 'juntaDirectiva.php');
$isCategoriasJunta = ($currentFile === 'categoriasJunta.php');
$isEmbajadores = ($currentFile === 'embajadores.php');
$isSocios = ($currentFile === 'socios.php');
$isSociosApolo = ($currentFile === 'socios_apolo.php');

// --- NUEVAS VARIABLES: GESTIÓN DOCUMENTAL ---
$isBandejaEntrada = ($currentFile === 'bandeja_entrada.php' || $currentFile === 'ver_documento_interno.php');
$isBandejaSalida = ($currentFile === 'bandeja_salida.php');
$isNuevoDocumento = ($currentFile === 'nuevo_documento.php');
// Variable para mantener abierto el acordeón si estamos en alguna de estas páginas
$isGestorActive = ($isBandejaEntrada || $isBandejaSalida || $isNuevoDocumento);

?>
<!doctype html>
<html class="no-js" lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>CAINEC - Dashboard</title>

    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/fav.png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* Color blanco para los botones de acordeón en el sidebar */
        #logo-sidebar button[data-accordion-target] {
            color: #ffffff !important;
        }

        /* Color blanco para los iconos dentro de esos botones */
        #logo-sidebar button[data-accordion-target] svg {
            color: #ffffff !important;
            fill: currentColor;
        }

        /* Efecto hover para que no se pierda el contraste al pasar el mouse */
        #logo-sidebar button[data-accordion-target]:hover {
            background-color: rgba(255, 255, 255, 0.1);
            /* Blanco muy transparente */
        }

        /* Color blanco para todos los enlaces (<a>) del sidebar */
        #logo-sidebar ul li a {
            color: #ffffff !important;
        }

        /* Color blanco para los iconos dentro de los enlaces */
        #logo-sidebar ul li a svg {
            color: #ffffff !important;
        }

        /* Color de texto cuando el enlace está activo (basado en tus reglas previas) */
        #logo-sidebar a.active,
        #logo-sidebar a[aria-current="page"] {
            color: #ffffff !important;
            background-color: #1e40af !important;
            /* Azul más fuerte para resaltar */
        }

        /* Efecto hover para enlaces simples */
        #logo-sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        :root {
            /* Colores Base */
            --tj-color-theme-primary: #0e2c5b;
            --tj-color-theme-bg: #344d6c;
        }

        /* Estilo para el enlace activo en el aside: azul más oscuro */
        #logo-sidebar a.active,
        #logo-sidebar a[aria-current="page"] {
            background-color: #1e40af;
            /* azul-800 */
            color: #ffffff !important;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05) inset;
        }

        /* Asegurar que el icono SVG también se vea en blanco */
        #logo-sidebar a.active svg,
        #logo-sidebar a[aria-current="page"] svg {
            color: #ffffff;
            fill: currentColor;
        }
    </style>
</head>

<body>
    <nav class="sticky top-0 z-50 w-full bg-[var(--tj-color-theme-primary)]">
        <div class="px-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end py-2">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="../index.php" class="flex ms-2 md:me-24">
                        <img src="../assets/images/logos/logo.webp" class="h-auto md:w-[130px] w-[70px] me-3 "
                            alt="CAINEC Logo" />
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full object-cover"
                                    src="<?php echo isset($_SESSION['user_img']) && !empty($_SESSION['user_img']) ? '../' . htmlspecialchars($_SESSION['user_img']) : '../assets/images/team/default-avatar.jpg'; ?>"
                                    onerror="this.onerror=null;this.src='../assets/images/recursos/placeholder.webp'"
                                    alt="user photo">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow border border-gray-300"
                            id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900" role="none">
                                    <?php echo isset($_SESSION['user_nombre']) ? htmlspecialchars($_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellido']) : 'Usuario'; ?>
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate" role="none">
                                    <?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem">Mi Perfil</a>
                                </li>
                                <li>
                                    <a href="entradas.php"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100<?php if (isset($isEntradas) && $isEntradas)
                                            echo ' active'; ?>"
                                        role="menuitem">Dashboard</a>
                                </li>
                                <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 1): ?>
                                    <li>
                                        <a href="usuarios.php"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100<?php if (isset($isUsuarios) && $isUsuarios)
                                                echo ' active'; ?>"
                                            role="menuitem">Usuarios</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                        role="menuitem">Cerrar Sesión</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full !bg-[var(--tj-color-theme-primary)] border-r sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-[var(--tj-color-theme-primary)]">
            <ul class="space-y-1 font-medium">

                <li class="px-2 pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">General</span>
                </li>

                <li>
                    <a href="entradas.php"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isEntradas) && $isEntradas)
                            echo ' active'; ?>"
                        <?php if (isset($isEntradas) && $isEntradas)
                            echo 'aria-current="page"'; ?>>
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 1): ?>
                    <li class="px-2 pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Contenido</span>
                    </li>

                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                            data-accordion-target="accordion-entradas">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M19 4h-1V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4Zm-2 14H4V2h12v16Z" />
                                <path d="M6 6h8v2H6V6Zm0 4h8v2H6v-2Zm0 4h5v2H6v-2Z" />
                            </svg>
                            <span class="flex-1 ms-3 text-left whitespace-nowrap">Blog</span>
                            <svg class="w-3 h-3 transition-transform" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <ul id="accordion-entradas" class="hidden py-2 space-y-2">
                            <li>
                                <a href="blogs.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isBlogs) && $isBlogs)
                                        echo ' active'; ?>"
                                    <?php if (isset($isBlogs) && $isBlogs)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Entradas</span>
                                </a>
                            </li>
                            <li>
                                <a href="categoriaBlogs.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isCategoriaBlogs) && $isCategoriaBlogs)
                                        echo ' active'; ?>"
                                    <?php if (isset($isCategoriaBlogs) && $isCategoriaBlogs)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Categorías</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="testimonios.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isTestimonios) && $isTestimonios)
                                echo ' active'; ?>"
                            <?php if (isset($isTestimonios) && $isTestimonios)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3.546l3.294 3.293a1 1 0 0 0 1.414 0l.966-.966 2.55 2.55a1 1 0 0 0 1.707-.707V14h.523a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM6 6h8v2H6V6Zm0 4h5v2H6v-2Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Testimonios</span>
                        </a>
                    </li>

                    <li class="px-2 pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestión Documental</span>
                    </li>

                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                            data-accordion-target="accordion-docs">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M18 0H2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM2 18V2h16v16H2Z" />
                                <path d="M6 6h8v2H6V6Zm0 4h8v2H6v-2Zm0 4h5v2H6v-2Z" />
                            </svg>
                            <span class="flex-1 ms-3 text-left whitespace-nowrap">Gestor Documental</span>
                            <svg class="w-3 h-3 transition-transform <?php echo $isGestorActive ? 'rotate-180' : ''; ?>"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <ul id="accordion-docs" class="<?php echo $isGestorActive ? '' : 'hidden'; ?> py-2 space-y-2">
                            <li>
                                <a href="bandeja_entrada.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isBandejaEntrada) && $isBandejaEntrada)
                                        echo ' active'; ?>"
                                    <?php if (isset($isBandejaEntrada) && $isBandejaEntrada)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Bandeja de Entrada</span>
                                </a>
                            </li>
                            <li>
                                <a href="bandeja_salida.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isBandejaSalida) && $isBandejaSalida)
                                        echo ' active'; ?>"
                                    <?php if (isset($isBandejaSalida) && $isBandejaSalida)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Bandeja de Salida</span>
                                </a>
                            </li>
                            <li>
                                <a href="nuevo_documento.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isNuevoDocumento) && $isNuevoDocumento)
                                        echo ' active'; ?>"
                                    <?php if (isset($isNuevoDocumento) && $isNuevoDocumento)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Nuevo Documento</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="px-2 pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Organización</span>
                    </li>

                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                            data-accordion-target="accordion-junta">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 2a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-4V4a2 2 0 0 0-2-2h-4zm0 4V4h4v2h-4z" />
                            </svg>
                            <span class="flex-1 ms-3 text-left whitespace-nowrap">Junta Directiva</span>
                            <svg class="w-3 h-3 transition-transform" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <ul id="accordion-junta" class="hidden py-2 space-y-2">
                            <li>
                                <a href="juntaDirectiva.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isJuntaDirectiva) && $isJuntaDirectiva)
                                        echo ' active'; ?>"
                                    <?php if (isset($isJuntaDirectiva) && $isJuntaDirectiva)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Listado Junta</span>
                                </a>
                            </li>
                            <li>
                                <a href="categoriasJunta.php"
                                    class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isCategoriasJunta) && $isCategoriasJunta)
                                        echo ' active'; ?>"
                                    <?php if (isset($isCategoriasJunta) && $isCategoriasJunta)
                                        echo 'aria-current="page"'; ?>>
                                    <span class="flex-1 ms-3 whitespace-nowrap">Categorías</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="embajadores.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isEmbajadores) && $isEmbajadores)
                                echo ' active'; ?>"
                            <?php if (isset($isEmbajadores) && $isEmbajadores)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Embajadores</span>
                        </a>
                    </li>

                    <li>
                        <a href="socios.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isSocios) && $isSocios)
                                echo ' active'; ?>"
                            <?php if (isset($isSocios) && $isSocios)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Miembros Cainec</span>
                        </a>
                    </li>
                    <li>
                        <a href="socios_apolo.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isSociosApolo) && $isSociosApolo)
                                echo ' active'; ?>"
                            <?php if (isset($isSociosApolo) && $isSociosApolo)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Insignias Apolo</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 1): ?>
                    <li class="px-2 pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</span>
                    </li>

                    <li>
                        <a href="usuarios.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isUsuarios) && $isUsuarios)
                                echo ' active'; ?>"
                            <?php if (isset($isUsuarios) && $isUsuarios)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="solicitudes.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isSolicitudes) && $isSolicitudes)
                                echo ' active'; ?>"
                            <?php if (isset($isSolicitudes) && $isSolicitudes)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 011-1h6a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h6a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h6a1 1 0 110 2H8a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Solicitudes de aprobacion</span>
                        </a>
                    </li>

                    <li>
                        <a href="smtp_config.php"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isCorreo) && $isCorreo)
                                echo ' active'; ?>"
                            <?php if (isset($isCorreo) && $isCorreo)
                                echo 'aria-current="page"'; ?>>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 16">
                                <path
                                    d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
                                <path
                                    d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Config. Correo</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="px-2 pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Cuenta</span>
                </li>

                <li>
                    <a href="perfil.php"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group<?php if (isset($isPerfil) && $isPerfil)
                            echo ' active'; ?>"
                        <?php if (isset($isPerfil) && $isPerfil)
                            echo 'aria-current="page"'; ?>>
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Mi Perfil</span>
                    </a>
                </li>

                <li>
                    <a href="logout.php"
                        class="flex items-center p-2 !text-red-500 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 !text-red-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64 min-h-screen bg-gray-50">

        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                try {
                    console.debug('[admin] Inicializando componentes del admin');

                    // ============================================
                    // 1. INICIALIZAR DROPDOWN DEL USUARIO EN NAV
                    // ============================================
                    const dropdownButton = document.querySelector('[data-dropdown-toggle="dropdown-user"]');
                    const dropdownMenu = document.getElementById('dropdown-user');

                    if (dropdownButton && dropdownMenu) {
                        console.debug('[admin] Dropdown usuario encontrado, inicializando');

                        // Toggle dropdown al hacer click
                        dropdownButton.addEventListener('click', function (e) {
                            e.stopPropagation();
                            const isHidden = dropdownMenu.classList.contains('hidden');

                            if (isHidden) {
                                dropdownMenu.classList.remove('hidden');
                                dropdownButton.setAttribute('aria-expanded', 'true');
                                console.debug('[admin] Dropdown abierto');
                            } else {
                                dropdownMenu.classList.add('hidden');
                                dropdownButton.setAttribute('aria-expanded', 'false');
                                console.debug('[admin] Dropdown cerrado');
                            }
                        });

                        // Cerrar dropdown al hacer click fuera
                        document.addEventListener('click', function (e) {
                            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                                dropdownMenu.classList.add('hidden');
                                dropdownButton.setAttribute('aria-expanded', 'false');
                            }
                        });
                    } else {
                        console.warn('[admin] No se encontró el dropdown del usuario');
                    }

                    // ============================================
                    // 2. TOGGLE SIDEBAR EN MÓVIL
                    // ============================================
                    const sidebarToggle = document.querySelector('[data-drawer-toggle="logo-sidebar"]');
                    const sidebar = document.getElementById('logo-sidebar');

                    if (sidebarToggle && sidebar) {
                        console.debug('[admin] Sidebar toggle encontrado, inicializando');

                        sidebarToggle.addEventListener('click', function (e) {
                            e.stopPropagation();
                            sidebar.classList.toggle('-translate-x-full');
                            const isOpen = !sidebar.classList.contains('-translate-x-full');
                            sidebarToggle.setAttribute('aria-expanded', isOpen.toString());
                            console.debug('[admin] Sidebar ' + (isOpen ? 'abierto' : 'cerrado'));
                        });

                        // Cerrar sidebar al hacer click fuera en móvil
                        document.addEventListener('click', function (e) {
                            if (window.innerWidth < 640) { // sm breakpoint
                                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                                    sidebar.classList.add('-translate-x-full');
                                    sidebarToggle.setAttribute('aria-expanded', 'false');
                                }
                            }
                        });
                    } else {
                        console.warn('[admin] No se encontró el sidebar toggle');
                    }

                    // ============================================
                    // ACCORDION FUNCTIONALITY FOR SIDEBAR
                    // ============================================
                    const accordionButtons = document.querySelectorAll('[data-accordion-target]');

                    if (accordionButtons.length > 0) {
                        console.debug('[admin] Accordion buttons encontrados:', accordionButtons.length);

                        accordionButtons.forEach(button => {
                            button.addEventListener('click', function (e) {
                                e.preventDefault();
                                const targetId = this.getAttribute('data-accordion-target');
                                const targetMenu = document.getElementById(targetId);
                                const chevron = this.querySelector('svg:last-child');

                                if (targetMenu) {
                                    // Toggle visibility
                                    targetMenu.classList.toggle('hidden');

                                    // Rotate chevron
                                    if (chevron) {
                                        if (targetMenu.classList.contains('hidden')) {
                                            chevron.style.transform = 'rotate(0deg)';
                                        } else {
                                            chevron.style.transform = 'rotate(180deg)';
                                        }
                                    }

                                    console.debug('[admin] Accordion toggled:', targetId);
                                }
                            });
                        });

                        // Auto-expand accordion if child is active
                        const activeLinks = document.querySelectorAll('#logo-sidebar a.active, #logo-sidebar a[aria-current="page"]');
                        activeLinks.forEach(link => {
                            const parentAccordion = link.closest('ul[id^="accordion-"]');
                            if (parentAccordion) {
                                parentAccordion.classList.remove('hidden');
                                const accordionId = parentAccordion.id;
                                const accordionButton = document.querySelector(`[data-accordion-target="${accordionId}"]`);
                                if (accordionButton) {
                                    const chevron = accordionButton.querySelector('svg:last-child');
                                    if (chevron) {
                                        chevron.style.transform = 'rotate(180deg)';
                                    }
                                }
                                console.debug('[admin] Auto-expanded accordion for active link:', accordionId);
                            }
                        });
                    }

                    // ============================================
                    // 3. INICIALIZAR MODALES GLOBALES (<dialog> HTML)
                    // ============================================
                    // Detectar todos los elementos <dialog>
                    const allDialogs = document.querySelectorAll('dialog');
                    console.debug('[admin] Modales <dialog> encontrados:', allDialogs.length);

                    allDialogs.forEach((dialog, index) => {
                        // Cerrar modal al hacer click en el backdrop (fuera del contenido)
                        dialog.addEventListener('click', function (e) {
                            // Si el click es directamente en el dialog (backdrop), no en su contenido
                            if (e.target === dialog) {
                                dialog.close();
                                console.debug('[admin] Modal cerrado por click en backdrop:', dialog.id || 'modal-' + index);
                            }
                        });

                        // Prevenir que clicks dentro del contenido del modal lo cierren
                        const dialogContent = dialog.querySelector('form, div');
                        if (dialogContent) {
                            dialogContent.addEventListener('click', function (e) {
                                e.stopPropagation();
                            });
                        }
                    });

                    // Flowbite modales (si hay con data-modal-toggle)
                    const modalTriggers = document.querySelectorAll('[data-modal-toggle]');
                    if (modalTriggers.length > 0) {
                        console.debug('[admin] Modales Flowbite encontrados:', modalTriggers.length);

                        modalTriggers.forEach((trigger, index) => {
                            trigger.addEventListener('click', function () {
                                const modalId = this.getAttribute('data-modal-toggle');
                                console.debug('[admin] Modal trigger clicked:', modalId);
                            });
                        });
                    }

                    // ============================================
                    // 4. MANEJO DE TABS (si existen en la página)
                    // ============================================
                    const tabButtons = document.querySelectorAll('[data-tabs-target]');
                    if (tabButtons.length > 0) {
                        console.debug('[admin] Tabs encontrados:', tabButtons.length);

                        tabButtons.forEach(button => {
                            button.addEventListener('click', function () {
                                const targetId = this.getAttribute('data-tabs-target');
                                console.debug('[admin] Tab clicked:', targetId);
                            });
                        });
                    }

                    // ============================================
                    // 5. MANEJO DE TOOLTIPS
                    // ============================================
                    const tooltips = document.querySelectorAll('[data-tooltip-target]');
                    if (tooltips.length > 0) {
                        console.debug('[admin] Tooltips encontrados:', tooltips.length);
                    }

                    // ============================================
                    // 6. ESCAPE KEY HANDLER GLOBAL
                    // ============================================
                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape') {
                            // Cerrar dropdown
                            if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                                dropdownMenu.classList.add('hidden');
                                dropdownButton.setAttribute('aria-expanded', 'false');
                                console.debug('[admin] Dropdown cerrado con Escape');
                            }

                            // Cerrar sidebar en móvil
                            if (sidebar && window.innerWidth < 640 && !sidebar.classList.contains('-translate-x-full')) {
                                sidebar.classList.add('-translate-x-full');
                                sidebarToggle.setAttribute('aria-expanded', 'false');
                                console.debug('[admin] Sidebar cerrado con Escape');
                            }

                            // Cerrar modales <dialog> abiertos con Escape (comportamiento nativo HTML mejorado)
                            allDialogs.forEach(dialog => {
                                if (dialog.open) {
                                    dialog.close();
                                    console.debug('[admin] Modal cerrado con Escape:', dialog.id);
                                }
                            });
                        }
                    });

                    console.debug('[admin] Inicialización completa');

                } catch (error) {
                    console.error('[admin] Error inicializando componentes:', error);
                }
            });

            // ============================================
            // FUNCIONES GLOBALES ÚTILES PARA PÁGINAS
            // ============================================

            // Función para abrir modal <dialog> (disponible globalmente)
            window.openModal = function (modalId) {
                const modal = document.getElementById(modalId);
                if (modal && modal.tagName === 'DIALOG') {
                    modal.showModal(); // Usa el método nativo de <dialog>
                    console.debug('[admin] Modal <dialog> abierto:', modalId);
                } else if (modal) {
                    // Fallback para modales no-<dialog> (Flowbite, etc.)
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    console.debug('[admin] Modal abierto:', modalId);
                }
            };

            // Función para cerrar modal <dialog> (disponible globalmente)
            window.closeModal = function (modalId) {
                const modal = document.getElementById(modalId);
                if (modal && modal.tagName === 'DIALOG') {
                    modal.close(); // Usa el método nativo de <dialog>
                    console.debug('[admin] Modal <dialog> cerrado:', modalId);
                } else if (modal) {
                    // Fallback para modales no-<dialog>
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = 'auto';
                    console.debug('[admin] Modal cerrado:', modalId);
                }
            };

            // Función para confirmar acciones (disponible globalmente)
            window.confirmAction = function (message, callback) {
                if (confirm(message)) {
                    callback();
                }
            };

        </script>