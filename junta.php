<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Obtener todos los socios activos ordenados
    $stmt = $pdo->query("SELECT * FROM socios WHERE estado = 1 ORDER BY orden ASC");
    $allSocios = $stmt->fetchAll();

    // Filtrar por tipo
    $institucionales = [];
    $naturales = [];
    $empresas = [];

    foreach ($allSocios as $socio) {
        // Decodificar servicios si es JSON
        if (!empty($socio['servicios'])) {
            $socio['servicios'] = json_decode($socio['servicios'], true) ?? [];
        } else {
            $socio['servicios'] = [];
        }

        switch ($socio['tipo']) {
            case 'institucional':
                $institucionales[] = $socio;
                break;
            case 'natural':
                $naturales[] = $socio;
                break;
            case 'empresa':
                $empresas[] = $socio;
                break;
        }
    }

} catch (PDOException $e) {
    // En caso de error, arrays vacíos para no romper la página
    error_log("Error DB en junta.php: " . $e->getMessage());
    $institucionales = [];
    $naturales = [];
    $empresas = [];
}

include 'components/header.php';
?>
<!-- end: Header Area -->


<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">
            <div class="space-for-header"></div>
            <!-- start: Breadcrumb Section -->
            <?php
            // Selección simple de imagen de fondo aleatoria entre 5 opciones
            $bgImages = [
                'assets/images/bg/img1.webp',
                'assets/images/bg/img2.webp',
                'assets/images/bg/img3.webp',
                'assets/images/bg/img4.webp',
                'assets/images/bg/img5.webp'

            ];
            $bg = $bgImages[array_rand($bgImages)];
            ?>
            <section class="tj-page-header section-gap-x" data-bg-image="<?= $bg ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tj-page-header-content text-center">
                                <h1 class="tj-page-title">Directorio Asociados</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Directorio Asociados</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Team Section -->
            <section class="tj-team-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i class="tji-box"></i>Conoce
                                    a los expertos</span>
                                <h2 class="sec-title title-anim">Socios <span>Institucionales.</span></h2>
                                <p class="desc wow fadeInUp" data-wow-delay=".3s">
                                    Profesionales certificados que impulsan la transformación del sector inmobiliario
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($institucionales as $index => $socio): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="service-item style-4 wow fadeInUp cursor-pointer"
                                    data-wow-delay=".<?= ($index + 1) ?>s"
                                    onclick="openModalCorporativo(<?= htmlspecialchars(json_encode($socio), ENT_QUOTES, 'UTF-8') ?>)">
                                    <div class="service-icon">
                                        <?php if (!empty($socio['imagen'])): ?>
                                            <img src="<?= htmlspecialchars($socio['imagen']) ?>"
                                                alt="<?= htmlspecialchars($socio['nombre']) ?>"
                                                class="w-16 h-16 object-contain">
                                        <?php else: ?>
                                            <i class="tji-service-1"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="service-content">
                                        <h4 class="title"><?= htmlspecialchars($socio['nombre']) ?></h4>
                                        <p class="desc"><?= htmlspecialchars($socio['descripcion_corta']) ?></p>
                                        <span class="text-btn">
                                            <span class="btn-text"><span>Ver más</span></span>
                                            <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
            </section>
            <!-- end: Team Section -->

            <!-- start: Personas Naturales Section -->
            <section class="tj-team-section section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i
                                        class="tji-box"></i>Nuestros Profesionales</span>
                                <h2 class="sec-title title-anim">Personas <span>Naturales.</span></h2>
                                <p class="desc wow fadeInUp" data-wow-delay=".3s">
                                    Expertos comprometidos con la excelencia en el sector inmobiliario
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($naturales as $index => $socio): ?>
                            <div class="col-lg-3 col-sm-6">
                                <div class="team-item wow fadeInUp cursor-pointer"
                                    data-wow-delay=".<?= ($index % 4 + 1) ?>s"
                                    onclick="openModalProfesional(<?= htmlspecialchars(json_encode($socio), ENT_QUOTES, 'UTF-8') ?>)">
                                    <div class="team-img">
                                        <div class="team-img-inner">
                                            <?php if (!empty($socio['imagen'])): ?>
                                                <img src="<?= htmlspecialchars($socio['imagen']) ?>"
                                                    alt="<?= htmlspecialchars($socio['nombre']) ?>"
                                                    class="w-full aspect-square object-cover object-top">
                                            <?php else: ?>
                                                <div
                                                    class="w-full aspect-square bg-gray-200 flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-user text-4xl"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="social-links">
                                            <ul>
                                                <?php if (!empty($socio['linkedin'])): ?>
                                                    <li><a href="<?= htmlspecialchars($socio['linkedin']) ?>" target="_blank"><i
                                                                class="fa-brands fa-linkedin-in"></i></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="team-content">
                                        <h4 class="title"><?= htmlspecialchars($socio['nombre']) ?></h4>
                                        <span class="designation"><?= htmlspecialchars($socio['cargo']) ?></span>
                                        <?php if (!empty($socio['email'])): ?>
                                            <a class="mail-at" href="mailto:<?= htmlspecialchars($socio['email']) ?>"><i
                                                    class="tji-at"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
            </section>
            <!-- end: Team Section -->
            <!-- start: Team Section -->
            <section class="tj-service-section service-4 section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i
                                        class="tji-box"></i>Nuestras alianzas</span>
                                <h2 class="sec-title title-anim">Empresas <span>Aliadas.</span></h2>
                                <p class="desc wow fadeInUp" data-wow-delay=".3s">
                                    Organizaciones estratégicas que fortalecen nuestra misión y amplían los beneficios
                                    para nuestros socios
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row row-gap-4">
                        <?php foreach ($empresas as $index => $socio): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="service-item style-4 wow fadeInUp cursor-pointer"
                                    data-wow-delay=".<?= ($index + 1) ?>s"
                                    onclick="openModalCorporativo(<?= htmlspecialchars(json_encode($socio), ENT_QUOTES, 'UTF-8') ?>)">
                                    <div class="service-icon">
                                        <?php if (!empty($socio['imagen'])): ?>
                                            <img src="<?= htmlspecialchars($socio['imagen']) ?>"
                                                alt="<?= htmlspecialchars($socio['nombre']) ?>"
                                                class="w-16 h-16 object-contain">
                                        <?php else: ?>
                                            <i class="tji-service-1"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="service-content">
                                        <h4 class="title"><?= htmlspecialchars($socio['nombre']) ?></h4>
                                        <p class="desc"><?= htmlspecialchars($socio['descripcion_corta']) ?></p>
                                        <span class="text-btn">
                                            <span class="btn-text"><span>Ver más</span></span>
                                            <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <?php include 'components/cta-3.php'; ?>


        </main>


        <!-- Modal Detalle Socio -->
        <dialog id="modalSocio" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto bg-white">
            <div class="!p-6 md:!p-14">
                <div class="px-8 pb-8 -mt-4 relative">
                     <!-- Header: Image/Logo & Name -->
                    <div class="flex flex-col sm:flex-row !items-center gap-6 !mb-6 md:!mb-10">
                        <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0 flex items-center justify-center">
                            <img id="modal-img" src="" alt="" class="w-full h-full object-cover">
                            <div id="modal-placeholder" class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 hidden">
                                <i id="modal-icon" class="tji-service-1 text-4xl"></i>
                            </div>
                        </div>

                        <div class="flex-1 text-center sm:text-left">
                            <h2 id="modal-nombre" class="!text-2xl font-bold text-gray-900 mb-1"></h2>
                            <p id="modal-cargo" class="text-[var(--tj-color-theme-primary)] font-medium text-lg mb-1"></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="!mb-6 md:!mb-10">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Descripción</h3>
                        <p id="modal-descripcion" class="text-gray-600 leading-relaxed whitespace-pre-line"></p>
                    </div>

                    <!-- Services / Specialties -->
                    <div id="modal-servicios-container" class="!mb-6 md:!mb-10 hidden">
                        <h3 id="modal-servicios-title" class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Servicios</h3>
                        <ul id="modal-servicios" class="list-disc list-inside text-gray-600"></ul>
                    </div>

                    <!-- Benefits (For Companies) -->
                    <div id="modal-beneficios-container" class="!mb-6 md:!mb-10 hidden">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Beneficios</h3>
                        <p id="modal-beneficios" class="text-gray-600 leading-relaxed"></p>
                    </div>

                     <!-- Education (For Professionals) -->
                    <div id="modal-educacion-container" class="!mb-6 md:!mb-10 hidden">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Educación</h3>
                        <p id="modal-educacion" class="text-gray-600 leading-relaxed"></p>
                    </div>

                    <!-- Contact -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div id="modal-email-container" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hidden">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Correo</p>
                                <a id="modal-email" href="" class="text-gray-900 hover:text-blue-600 font-medium truncate block"></a>
                            </div>
                        </div>

                        <div id="modal-telefono-container" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hidden">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Teléfono</p>
                                <a id="modal-telefono" href="" class="text-gray-900 hover:text-green-600 font-medium"></a>
                            </div>
                        </div>

                         <div id="modal-website-container" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hidden">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Sitio Web</p>
                                <a id="modal-website" href="" target="_blank" class="text-gray-900 hover:text-purple-600 font-medium truncate block"></a>
                            </div>
                        </div>

                         <div id="modal-linkedin-container" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hidden">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-500 uppercase font-semibold">LinkedIn</p>
                                <a id="modal-linkedin" href="" target="_blank" class="text-gray-900 hover:text-blue-700 font-medium truncate block">Ver Perfil</a>
                            </div>
                        </div>
                    </div>
                     <div class="mt-8 text-center">
                        <button onclick="document.getElementById('modalSocio').close()" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full font-medium transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </dialog>

        <script>
        function openModalCorporativo(socio) {
            populateModal(socio, 'corporativo');
            document.getElementById('modalSocio').showModal();
        }

        function openModalProfesional(socio) {
            populateModal(socio, 'profesional');
            document.getElementById('modalSocio').showModal();
        }

        function populateModal(socio, type) {
            // Basic Info
            document.getElementById('modal-nombre').textContent = socio.nombre;
            document.getElementById('modal-descripcion').textContent = socio.descripcion_completa || socio.descripcion_corta;

            // Image/Icon
            const img = document.getElementById('modal-img');
            const placeholder = document.getElementById('modal-placeholder');
            const icon = document.getElementById('modal-icon');

            if (socio.imagen) {
                img.src = socio.imagen;
                img.classList.remove('hidden');
                if(type === 'corporativo') {
                     img.classList.add('object-contain', 'p-2');
                     img.classList.remove('object-cover');
                } else {
                     img.classList.add('object-cover');
                     img.classList.remove('object-contain', 'p-2');
                }
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
                // Default icon
                icon.className = type === 'corporativo' ? 'tji-service-1 text-4xl' : 'fa-solid fa-user text-4xl';
            }

            // Type specific fields
            const cargoEl = document.getElementById('modal-cargo');
            const serviciosContainer = document.getElementById('modal-servicios-container');
            const serviciosList = document.getElementById('modal-servicios');
            const serviciosTitle = document.getElementById('modal-servicios-title');
            const beneficiosContainer = document.getElementById('modal-beneficios-container');
            const beneficiosEl = document.getElementById('modal-beneficios');
            const educacionContainer = document.getElementById('modal-educacion-container');
            const educacionEl = document.getElementById('modal-educacion');

            // Reset visibility
            serviciosContainer.classList.add('hidden');
            beneficiosContainer.classList.add('hidden');
            educacionContainer.classList.add('hidden');
            cargoEl.textContent = '';

            if (type === 'corporativo') {
                // Services
                if (socio.servicios && socio.servicios.length > 0) {
                    serviciosTitle.textContent = 'Servicios';
                    serviciosList.innerHTML = socio.servicios.map(s => `<li>${s}</li>`).join('');
                    serviciosContainer.classList.remove('hidden');
                }
                // Benefits
                if (socio.beneficios) {
                    beneficiosEl.textContent = socio.beneficios;
                    beneficiosContainer.classList.remove('hidden');
                }
            } else {
                // Cargo
                cargoEl.textContent = socio.cargo;
                // Specialties (mapped to services in DB)
                if (socio.servicios && socio.servicios.length > 0) {
                    serviciosTitle.textContent = 'Especialidades';
                    serviciosList.innerHTML = socio.servicios.map(s => `<li>${s}</li>`).join('');
                    serviciosContainer.classList.remove('hidden');
                }
                // Education
                if (socio.educacion) {
                    educacionEl.textContent = socio.educacion;
                    educacionContainer.classList.remove('hidden');
                }
            }

            // Contact Info
            updateLink('modal-email-container', 'modal-email', socio.email, `mailto:${socio.email}`, socio.email);
            updateLink('modal-telefono-container', 'modal-telefono', socio.telefono, `tel:${socio.telefono}`, socio.telefono);
            updateLink('modal-website-container', 'modal-website', socio.website, socio.website, socio.website);
            updateLink('modal-linkedin-container', 'modal-linkedin', socio.linkedin, socio.linkedin, 'Ver Perfil');
        }

        function updateLink(containerId, linkId, value, href, text) {
            const container = document.getElementById(containerId);
            const link = document.getElementById(linkId);
            if (value) {
                link.href = href;
                link.textContent = text;
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        // Close modal when clicking outside
        document.getElementById('modalSocio').addEventListener('click', (e) => {
            const dialogDimensions = document.getElementById('modalSocio').getBoundingClientRect();
            if (
                e.clientX < dialogDimensions.left ||
                e.clientX > dialogDimensions.right ||
                e.clientY < dialogDimensions.top ||
                e.clientY > dialogDimensions.bottom
            ) {
                document.getElementById('modalSocio').close();
            }
        });
        </script>

        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>