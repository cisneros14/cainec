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
                                <h1 class="tj-page-title">Conoce las alianzas nacionales e internacionales</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Alianzas</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Team Section -->
            <style>
                /* Asegurar que todas las imágenes de alianzas tengan la misma altura */
                .tj-team-section .team-img-inner {
                    min-height: 280px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .tj-team-section .team-img-inner img {
                    max-width: 100%;
                    height: auto;
                    object-fit: contain;
                }
            </style>
            <section class="tj-team-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i class="tji-box"></i>Meet
                                    Our Team</span>
                                <h2 class="sec-title title-anim">Alianzas <span>Nacionales.</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-12">
                            <div class="team-item wow fadeInUp" data-wow-delay=".7s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="https://cainec.com/wp-content/uploads/2022/05/logo_mls-1.jpg" alt="MLS ACBIR / CAINEC">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://cainec.com/wp-content/uploads/2022/05/logo_mls-1.jpg" target="_blank"><i
                                                        class="fa-solid fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="https://cainec.com/wp-content/uploads/2022/05/logo_mls-1.jpg" target="_blank">Multiple
                                            Listing Service</a></h4>
                                    <span class="designation">Convenio de beneficios económicos para los asociados de
                                        CAINEC, con 20% de descuento en la membresía anual del Ecosistema Inmobiliario
                                        del Ecuador.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-12">
                            <div class="team-item wow fadeInUp" data-wow-delay=".1s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="https://cainec.com/wp-content/uploads/2022/05/logo_entorno_aprendizaje2.jpg"
                                            alt="Entorno de Aprendizaje Inmobiliario">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://cainec.com/wp-content/uploads/2022/05/logo_entorno_aprendizaje2.jpg"
                                                    target="_blank"><i class="fa-solid fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="https://cainec.com/wp-content/uploads/2022/05/logo_entorno_aprendizaje2.jpg"
                                            target="_blank">Entorno de Aprendizaje Inmobiliario</a></h4>
                                    <span class="designation">Plataforma exclusiva de e-learning para socios de CAINEC,
                                        que promueve la capacitación continua y el desarrollo profesional en el sector
                                        inmobiliario.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4 col-12">
                            <div class="team-item wow fadeInUp" data-wow-delay=".3s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="https://cainec.com/wp-content/uploads/2022/12/logo_advance_consultora.jpg" alt="Advance Consultora">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://cainec.com/wp-content/uploads/2022/12/logo_advance_consultora.jpg" target="_blank"><i
                                                        class="fa-solid fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="https://cainec.com/wp-content/uploads/2022/12/logo_advance_consultora.jpg" target="_blank">Advance
                                            Consultora</a></h4>
                                    <span class="designation">Firma especializada en análisis e inteligencia de mercado,
                                        basada en las tres C’s: Calidad, Compromiso y Confianza.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="team-item wow fadeInUp" data-wow-delay=".5s">
                                <div class="team-img">
                                    <div class="team-img-inner !bg-white !flex !items-center !justify-center">
                                        <img src="https://cainec.com/wp-content/uploads/2023/04/Logos-utpl.png"
                                            alt="Universidad Técnica Particular de Loja">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://cainec.com/wp-content/uploads/2023/04/Logos-utpl.png" target="_blank"><i
                                                        class="fa-solid fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="https://cainec.com/wp-content/uploads/2023/04/Logos-utpl.png" target="_blank">Universidad
                                            Técnica Particular de Loja</a></h4>
                                    <span class="designation">Alianza académica que otorga becas, tarifas preferenciales
                                        y promueve la vinculación laboral de los afiliados de CAINEC.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="team-item wow fadeInUp" data-wow-delay=".7s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="https://cainec.com/wp-content/uploads/2023/04/centro-de-mediacion-del-Azuay-e1680750859365.png"
                                            alt="Centro de Arbitraje y Mediación del Azuay">
                                    </div>
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="https://cainec.com/wp-content/uploads/2023/04/centro-de-mediacion-del-Azuay-e1680750859365.png" target="_blank"><i
                                                        class="fa-solid fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title"><a href="https://cainec.com/wp-content/uploads/2023/04/centro-de-mediacion-del-Azuay-e1680750859365.png"
                                            target="_blank">Centro de Arbitraje y Mediación del Azuay</a></h4>
                                    <span class="designation">Alianza para la formación en mediación y resolución de
                                        conflictos, con servicios preferenciales para afiliados de CAINEC.</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- end: Team Section -->
            <!-- start: Team Section -->
            <section class="tj-team-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i class="tji-box"></i>Meet
                                    Our Team</span>
                                <h2 class="sec-title title-anim">Alianzas <span>Internacionales.</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".1s">
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
                                    <h4 class="title"><a href="team-details.html">Eade Marren</a></h4>
                                    <span class="designation">Chief Executive</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".3s">
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
                                    <h4 class="title"><a href="team-details.html">Savannah Ngueen</a></h4>
                                    <span class="designation">Operations Head</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".5s">
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
                                    <h4 class="title"><a href="team-details.html">Kristin Watson</a></h4>
                                    <span class="designation">Marketing Lead</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".7s">
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
                                    <h4 class="title"><a href="team-details.html">Darlene Robertson</a></h4>
                                    <span class="designation">Business Director</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".1s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-5.webp" alt="">
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
                                    <h4 class="title"><a href="team-details.html">Darlene Robertson</a></h4>
                                    <span class="designation">Business Director</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".3s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-6.webp" alt="">
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
                                    <h4 class="title"><a href="team-details.html">Kristin Watson</a></h4>
                                    <span class="designation">Marketing Lead</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".5s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-7.webp" alt="">
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
                                    <h4 class="title"><a href="team-details.html">Savannah Ngueen</a></h4>
                                    <span class="designation">Operations Head</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team-item wow fadeInUp" data-wow-delay=".7s">
                                <div class="team-img">
                                    <div class="team-img-inner">
                                        <img src="assets/images/team/team-8.webp" alt="">
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
                                    <h4 class="title"><a href="team-details.html">Eade Marren</a></h4>
                                    <span class="designation">Chief Executive</span>
                                    <a class="mail-at" href="mailto:info@bexon.com"><i class="tji-at"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Team Section -->
        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>