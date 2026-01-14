<?php include 'components/header.php'; ?>
<!-- end: Header Area -->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">

            <div class="space-for-header"></div>
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
                                <h1 class="tj-page-title">Sobre Nosotros</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Sobre Nosotros</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Choose Section -->
            <section id="choose" class="tj-choose-section section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading-wrap">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".3s"><i class="tji-box"></i>Conoce
                                    CAINEC</span>
                                <div class="heading-wrap-content">
                                    <div class="sec-heading">
                                        <h2 class="sec-title title-anim">Impulsando el sector inmobiliario con
                                            <span>profesionalismo.</span>
                                        </h2>
                                    </div>
                                    <div class="btn-wrap wow fadeInUp" data-wow-delay=".6s">
                                        <a class="tj-primary-btn" href="register.php">
                                            <span class="btn-text"><span>Unirme como socio CAINEC</span></span>
                                            <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-gap-4 rightSwipeWrap">
                        <div class="col-lg-4">
                            <div class="choose-box right-swipe">
                                <div class="choose-content">
                                    <div class="choose-icon">
                                        <i class="tji-award"></i>
                                    </div>
                                    <h4 class="title">¿Quiénes somos?</h4>
                                    <p class="desc">CAINEC es un organismo nacional que integra a profesionales,
                                        empresas y organizaciones del sector inmobiliario, promoviendo su desarrollo,
                                        profesionalización y crecimiento. Su objetivo es generar un impacto positivo
                                        mediante la formalización, calidad de servicio y participación activa en la
                                        gobernanza del sector en beneficio del país.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="choose-box right-swipe">
                                <div class="choose-content">
                                    <div class="choose-icon">
                                        <i class="tji-innovative"></i>
                                    </div>
                                    <h4 class="title">Motivación</h4>
                                    <p class="desc">Tras cuatro décadas de historia y de la implementación de normativas
                                        para regular la
                                        intermediación inmobiliaria en el Ecuador —sin resultados significativos y con
                                        un aumento evidente
                                        de la informalidad que afecta la credibilidad y la responsabilidad tributaria
                                        del sector—, nace la
                                        Cámara Inmobiliaria Ecuatoriana: un organismo renovador, activo y despolitizado,
                                        comprometido con
                                        recuperar la imagen y la confianza del gremio inmobiliario.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="choose-box right-swipe">
                                <div class="choose-content">
                                    <div class="choose-icon">
                                        <i class="tji-support"></i>
                                    </div>
                                    <h4 class="title">Propósito</h4>
                                    <p class="desc">Impulsar un cambio positivo y sostenible en la industria
                                        inmobiliaria del Ecuador,
                                        promoviendo la formalización, la excelencia en el servicio y la participación
                                        activa en la
                                        gobernanza sectorial, mediante acciones y propuestas que defiendan los intereses
                                        de nuestros
                                        asociados y contribuyan al desarrollo social y económico del país.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Choose Section -->

            <!-- start: About Section -->
            <section class="tj-about-section-2 section-gap section-gap-x !bg-[var(--tj-color-theme-primary)]">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 order-lg-1 order-2">
                            <div class="about-img-area style-2 wow fadeInLeft" data-wow-delay=".3s">
                                <div class="about-img overflow-hidden">
                                    <img data-speed=".8" src="assets/images/recursos/img18.png"
                                        alt="Imagen sobre nosotros">
                                </div>

                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 order-lg-2 order-1">
                            <div class="about-content-area">
                                <div class="sec-heading">
                                    <span class="sub-title wow fadeInUp !text-white" data-wow-delay=".3s"><i
                                            class="tji-box !text-white"></i>Conócenos</span>
                                    <h2 class="sec-title title-anim text-white">Impulsando la innovación y la excelencia
                                        para un éxito sostenible.
                                    </h2>
                                </div>
                            </div>
                            <div class="about-bottom-area">
                                <div class="mission-vision-box wow fadeInLeft" data-wow-delay=".5s">
                                    <h4 class="title">Nuestra Misión</h4>
                                    <p class="desc">Nuestra misión es fortalecer la industria inmobiliaria del Ecuador a
                                        través de la formalización,
                                        la innovación y la excelencia en el servicio.
                                    </p>
                                    <ul class="list-items">
                                        <li><i class="tji-list"></i>Innovación y Excelencia</li>
                                        <li><i class="tji-list"></i>Calidad y Profesionalismo</li>
                                        <li><i class="tji-list"></i>Desarrollo del Sector</li>
                                    </ul>
                                </div>
                                <div class="mission-vision-box wow fadeInRight" data-wow-delay=".5s">
                                    <h4 class="title">Nuestra Visión</h4>
                                    <p class="desc">Nuestra visión es consolidarnos como el organismo líder que impulsa
                                        el crecimiento, la ética y la
                                        confianza en el sector inmobiliario ecuatoriano.
                                    </p>
                                    <ul class="list-items">
                                        <li><i class="tji-list"></i>Liderazgo Institucional</li>
                                        <li><i class="tji-list"></i>Impacto Transformador</li>
                                        <li><i class="tji-list"></i>Crecimiento Sostenible</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="about-btn-area wow fadeInUp" data-wow-delay=".6s">
                                <a class="tj-primary-btn" href="register.php">
                                    <span class="btn-text"><span>Únete a CAINEC</span></span>
                                    <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-shape-1">
                    <img src="assets/images/shape/pattern-2.svg" alt="">
                </div>
                <div class="bg-shape-2">
                    <img src="assets/images/shape/pattern-3.svg" alt="">
                </div>
            </section>
            <!-- end: About Section -->

        <?php include 'components/carrusel-aliados.php'; ?>


            <!-- start: Testimonial Section -->
            <section class="tj-testimonial-section-2 section-bottom-gap">
                <div class="container">
                    <div class="row row-gap-3">
                        <div class="col-lg-6 order-lg-2">
                            <div class="testimonial-img-area wow fadeInUp" data-wow-delay=".3s">
                                <div class="testimonial-img">
                                    <img src="assets/images/recursos/img17.png" alt="">
                                    <div class="sec-heading style-2">
                                        <h2 class="sec-title title-anim">Testimonios de <span>nuestros clientes.</span>
                                        </h2>
                                    </div>
                                </div>
                                <div class="box-area">
                                    <div class="rating-box wow fadeInUp" data-wow-delay=".5s">
                                        <h2 class="title">4.9</h2>
                                        <div class="rating-area">
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: 100%">
                                                    <span>★★★★★</span>
                                                </div>
                                                <div class="empty-ratings">
                                                    <span>★★★★★</span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="rating-text" id="testimonios-count">(80+ reseñas de
                                            clientes)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-lg-1">
                            <div class="testimonial-wrapper wow fadeInUp" data-wow-delay=".5s">
                                <div class="swiper testimonial-slider-2">
                                    <div class="swiper-wrapper" id="testimonios-slider">
                                        <!-- Los testimonios se cargarán dinámicamente -->
                                        <div class="swiper-slide">
                                            <div class="testimonial-item">
                                                <span class="quote-icon"><i class="tji-quote"></i></span>
                                                <div class="desc">
                                                    <p>Cargando testimonios...</p>
                                                </div>
                                                <div class="testimonial-author">
                                                    <div class="author-inner">
                                                        <div class="author-img">
                                                            <img src="assets/images/testimonial/client-1.webp" alt="">
                                                        </div>
                                                        <div class="author-header">
                                                            <h4 class="title">Cargando...</h4>
                                                            <span class="designation">...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-pagination-area"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Testimonial Section -->

            <script>
                // Cargar testimonios desde la base de datos
                document.addEventListener('DOMContentLoaded', async function () {
                    try {
                        const response = await fetch('admin/api/testimonios/listar-publico.php');
                        const data = await response.json();

                        if (data.success && data.data.length > 0) {
                            const testimonios = data.data;
                            const sliderWrapper = document.getElementById('testimonios-slider');

                            // Actualizar el contador
                            const countElement = document.getElementById('testimonios-count');
                            if (countElement) {
                                countElement.textContent = `(${testimonios.length}+ reseñas de clientes)`;
                            }

                            // Generar los slides de testimonios
                            sliderWrapper.innerHTML = testimonios.map(testimonio => {
                                const fotoUrl = testimonio.foto_url
                                    ? testimonio.foto_url
                                    : 'assets/images/testimonial/client-1.webp';

                                return `
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <span class="quote-icon"><i class="tji-quote"></i></span>
                                        <div class="desc">
                                            <p>${escapeHtml(testimonio.testimonio)}</p>
                                        </div>
                                        <div class="testimonial-author">
                                            <div class="author-inner">
                                                <div class="author-img">
                                                    <img src="${escapeHtml(fotoUrl)}" alt="${escapeHtml(testimonio.nombre)}" onerror="this.src='assets/images/testimonial/client-1.webp'">
                                                </div>
                                                <div class="author-header">
                                                    <h4 class="title">${escapeHtml(testimonio.nombre)}</h4>
                                                    <span class="designation">${testimonio.cargo ? escapeHtml(testimonio.cargo) : 'Cliente'}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            }).join('');

                            // Reinicializar el slider de Swiper si existe
                            if (typeof Swiper !== 'undefined') {
                                // Destruir instancia anterior si existe
                                const existingSwiper = document.querySelector('.testimonial-slider-2').swiper;
                                if (existingSwiper) {
                                    existingSwiper.destroy(true, true);
                                }

                                // Crear nueva instancia
                                new Swiper('.testimonial-slider-2', {
                                    slidesPerView: 1,
                                    spaceBetween: 30,
                                    loop: testimonios.length > 1,
                                    autoplay: {
                                        delay: 5000,
                                        disableOnInteraction: false,
                                    },
                                    pagination: {
                                        el: '.swiper-pagination-area',
                                        clickable: true,
                                    },
                                    speed: 1000,
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error al cargar testimonios:', error);
                    }

                    function escapeHtml(text) {
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    }
                });
            </script>

            <!-- start: Team Section -->
            <section class="tj-team-section-3 section-gap section-gap-x !bg-white">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".3s"><i class="tji-box"></i> Conoce
                                    más</span>
                                <h2 class="sec-title title-anim">Nuestros <span>socios</span> hacen la diferencia</h2>
                                <p class="desc wow fadeInUp" data-wow-delay=".5s">
                                    Profesionales comprometidos con la excelencia y el desarrollo del sector
                                    inmobiliario ecuatoriano. Únete a nuestra red de expertos.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center mt-4 mb-5 wow fadeInUp" data-wow-delay=".6s">
                                <a class="tj-primary-btn !rounded-xl" href="register.php">
                                    <span class="btn-text"><span>Únete como socio CAINEC</span></span>
                                    <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                </a>
                                <p class="mt-3 text-gray-600">Forma parte de la cámara que impulsa el cambio en la
                                    industria inmobiliaria</p>
                            </div>
                        </div>
                    </div>

                    <div class="row leftSwipeWrap">
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item left-swipe">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-1.webp" alt="">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://www.facebook.com/" target="_blank"><i
                                                        class="fa-brands fa-facebook-f"></i></a>
                                            </li>
                                            <li><a href="https://www.instagram.com/" target="_blank"><i
                                                        class="fa-brands fa-instagram"></i></a>
                                            </li>
                                            <li><a href="https://x.com/" target="_blank"><i
                                                        class="fa-brands fa-x-twitter"></i></a></li>
                                            <li><a href="https://www.linkedin.com/" target="_blank"><i
                                                        class="fa-brands fa-linkedin-in"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="team-details.php">María Fernanda González</a></h4>
                                    <span class="designation">Arquitecta Senior</span>
                                    <a class="mail-at" href="mailto:mf.gonzalez@cainec.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item left-swipe">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-2.webp" alt="">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://www.facebook.com/" target="_blank"><i
                                                        class="fa-brands fa-facebook-f"></i></a>
                                            </li>
                                            <li><a href="https://www.instagram.com/" target="_blank"><i
                                                        class="fa-brands fa-instagram"></i></a>
                                            </li>
                                            <li><a href="https://x.com/" target="_blank"><i
                                                        class="fa-brands fa-x-twitter"></i></a></li>
                                            <li><a href="https://www.linkedin.com/" target="_blank"><i
                                                        class="fa-brands fa-linkedin-in"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="team-details.php">Carlos Andrés Morales</a></h4>
                                    <span class="designation">Ingeniero Civil</span>
                                    <a class="mail-at" href="mailto:ca.morales@cainec.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item left-swipe">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-3.webp" alt="">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://www.facebook.com/" target="_blank"><i
                                                        class="fa-brands fa-facebook-f"></i></a>
                                            </li>
                                            <li><a href="https://www.instagram.com/" target="_blank"><i
                                                        class="fa-brands fa-instagram"></i></a>
                                            </li>
                                            <li><a href="https://x.com/" target="_blank"><i
                                                        class="fa-brands fa-x-twitter"></i></a></li>
                                            <li><a href="https://www.linkedin.com/" target="_blank"><i
                                                        class="fa-brands fa-linkedin-in"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="team-details.php">Andrea Patricia Silva</a></h4>
                                    <span class="designation">Abogada Inmobiliaria</span>
                                    <a class="mail-at" href="mailto:ap.silva@cainec.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item left-swipe">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-4.webp" alt="">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://www.facebook.com/" target="_blank"><i
                                                        class="fa-brands fa-facebook-f"></i></a>
                                            </li>
                                            <li><a href="https://www.instagram.com/" target="_blank"><i
                                                        class="fa-brands fa-instagram"></i></a>
                                            </li>
                                            <li><a href="https://x.com/" target="_blank"><i
                                                        class="fa-brands fa-x-twitter"></i></a></li>
                                            <li><a href="https://www.linkedin.com/" target="_blank"><i
                                                        class="fa-brands fa-linkedin-in"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="team-details.php">Roberto Alejandro Vega</a></h4>
                                    <span class="designation">Corredor Inmobiliario</span>
                                    <a class="mail-at" href="mailto:ra.vega@cainec.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-shape-1">
                    <img src="assets/images/shape/pattern-2.svg" alt="">
                </div>
                <div class="bg-shape-2">
                    <img src="assets/images/shape/pattern-3.svg" alt="">
                </div>
            </section>
            <!-- end: Team Section -->

            <!-- start: Faq Section -->
            <section class="tj-faq-section section-gap">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-lg-4">
                            <div class="content-wrap">
                                <div class="sec-heading">
                                    <span class="sub-title wow fadeInUp" data-wow-delay=".3s"><i
                                            class="tji-box"></i>Preguntas
                                        frecuentes</span>
                                    <h2 class="sec-title title-anim">¿Necesitas <span>ayuda?</span> Comienza aquí...
                                    </h2>
                                </div>
                                <p class="desc wow fadeInUp" data-wow-delay=".6s">Nos mantenemos a la vanguardia,
                                    aprovechando
                                    tecnologías y estrategias de última generación para ofrecer soluciones competitivas.
                                </p>
                                <div class="wow fadeInUp" data-wow-delay=".8s">
                                    <a class="tj-primary-btn" href="contact.php">
                                        <span class="btn-text"><span>Solicitar una llamada</span></span>
                                        <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="accordion tj-faq" id="faqOne">
                                <?php
                                // Cargar FAQs desde la base de datos (solo activas)
                                $faqs = [];
                                try {
                                    require_once __DIR__ . '/config.php';
                                    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                                    $pdo = new PDO($dsn, DB_USER, DB_PASS);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                                    $stmt = $pdo->prepare("SELECT pregunta, respuesta FROM faqs WHERE estado = 'activo' ORDER BY creado_en DESC");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();

                                    foreach ($rows as $r) {
                                        $faqs[] = ['question' => $r['pregunta'], 'answer' => $r['respuesta']];
                                    }
                                } catch (PDOException $e) {
                                    error_log('Error cargando FAQs en about.php: ' . $e->getMessage());
                                    $faqs = [];
                                }

                                // Renderizar manteniendo la UX: clases wow/data-wow-delay secuenciales
                                foreach ($faqs as $index => $faq) {
                                    $id = 'faq-' . ($index + 1);
                                    $isFirst = $index === 0;
                                    $itemClass = 'accordion-item' . ($isFirst ? ' active' : '');
                                    $collapseClass = $isFirst ? 'collapse show' : 'collapse';
                                    $buttonClass = 'faq-title' . ($isFirst ? '' : ' collapsed');
                                    // Generar delays .3s, .4s, .5s ...
                                    $delay = '.' . ($index + 3) . 's';

                                    echo '<div class="' . $itemClass . ' wow fadeInUp" data-wow-delay="' . $delay . '">';
                                    echo '<button class="' . $buttonClass . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $id . '" aria-expanded="' . ($isFirst ? 'true' : 'false') . '">' . htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') . '</button>';
                                    echo '<div id="' . $id . '" class="' . $collapseClass . '" data-bs-parent="#faqOne">';
                                    echo '<div class="accordion-body faq-text">';
                                    echo '<p>' . nl2br(htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8')) . '</p>';
                                    echo '</div>'; // accordion-body
                                    echo '</div>'; // collapse
                                    echo '</div>'; // accordion-item
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <style>
                /* Asegurar que el contenido del acordeón se muestre correctamente */
                .tj-faq .collapse.show {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }

                .tj-faq .collapse:not(.show) {
                    display: none !important;
                }

                .tj-faq .accordion-body {
                    display: block !important;
                    visibility: visible !important;
                }
            </style>
            <!-- start: Footer Section -->
            <?php include 'components/footer.php'; ?>