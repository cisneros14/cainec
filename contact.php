<?php include 'components/header.php'; ?>

<!-- end: Header Area -->

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
                                <h1 class="tj-page-title">Información de Contacto</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Información de Contacto</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Contact Top Section -->
            <div class="tj-contact-area section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i
                                        class="tji-box"></i>Información de Contacto</span>
                                <h2 class="sec-title title-anim"><span>Llega</span> a Nosotros</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row row-gap-4">
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="contact-item style-2 wow fadeInUp" data-wow-delay=".3s">
                                <div class="contact-icon !bg-[#0e2c5b]">
                                    <i class="tji-location-3 !text-white"></i>
                                </div>
                                <h3 class="contact-title">Nuestras Oficinas</h3>
                                <p>Av. Remigio Crespo Toral y Calle Antonio Tamariz, Cuenca – Ecuador</p>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="contact-item style-2 wow fadeInUp" data-wow-delay=".5s">
                                <div class="contact-icon !bg-[#0e2c5b]">
                                    <i class="tji-envelop !text-white"></i>
                                </div>
                                <h3 class="contact-title">Correo Electrónico</h3>
                                <ul class="contact-list">
                                    <li><a href="mailto:info@cainec.com">info@cainec.com</a></li>
                                    <li><a href="mailto:info@cainec.com">info@cainec.com</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="contact-item style-2 wow fadeInUp" data-wow-delay=".7s">
                                <div class="contact-icon !bg-[#0e2c5b]">
                                    <i class="tji-phone !text-white"></i>
                                </div>
                                <h3 class="contact-title">Llámanos</h3>
                                <ul class="contact-list">
                                    <li><a href="tel:+59374075807">+593 07 4075807</a></li>
                                    <li><a href="tel:+593995453741">+593 99 545 3741</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="contact-item style-2 wow fadeInUp" data-wow-delay=".9s">
                                <div class="contact-icon !bg-[#0e2c5b]">
                                    <i class="tji-chat !text-white"></i>
                                </div>
                                <h3 class="contact-title">Delegaciones & Contactos</h3>
                                <ul class="contact-list">
                                    <li>Pichincha: <a href="mailto:pichincha@cainec.com">pichincha@cainec.com</a></li>
                                    <li>Azuay: <a href="mailto:azuay@cainec.com">azuay@cainec.com</a></li>
                                    <li>Cañar: <a href="mailto:canar@cainec.com">canar@cainec.com</a></li>
                                    <li>Guayas: <a href="mailto:guayas@cainec.com">guayas@cainec.com</a></li>
                                    <li>Loja: <a href="mailto:loja@cainec.com">loja@cainec.com</a></li>
                                    <li>Santa Elena: <a href="mailto:santaelena@cainec.com">santaelena@cainec.com</a>
                                    </li>
                                    <li>El Oro: <a href="mailto:eloro@cainec.com">eloro@cainec.com</a></li>
                                    <li>Esmeraldas: <a href="mailto:esmeraldas@cainec.com">esmeraldas@cainec.com</a>
                                    </li>
                                    <li>Manabí: <a href="mailto:manabi@cainec.com">manabi@cainec.com</a></li>
                                </ul>
                                <div style="margin-top:.5rem;font-size:.95rem">
                                    <strong>Cámara Inmobiliaria Ecuatoriana - CAINEC</strong>
                                    <div>Síguenos: <a href="https://www.instagram.com/cainecec"
                                            target="_blank">@cainecec</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: Contact Top Section -->

            <!-- start: Contact Section -->
            <section class="tj-contact-section-2 section-bottom-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="contact-form wow fadeInUp" data-wow-delay=".1s">
                                <h3 class="title">Contáctanos o visítanos</h3>
                                <form id="contact-form">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-input">
                                                <input type="text" name="cfName">
                                                <label class="cf-label">Nombre completo <span>*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-input">
                                                <input type="email" name="cfEmail">
                                                <label class="cf-label">Correo electrónico <span>*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-input">
                                                <input type="tel" name="cfPhone">
                                                <label class="cf-label">Teléfono <span>*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-input">
                                                <div class="tj-nice-select-box">
                                                    <div class="tj-select">
                                                        <select name="cfSubject">
                                                            <option value="0">Selecciona una opción</option>
                                                            <option value="1">Estrategia de negocio</option>
                                                            <option value="2">Experiencia del cliente</option>
                                                            <option value="3">Sostenibilidad y ESG</option>
                                                            <option value="4">Capacitación y desarrollo</option>
                                                            <option value="5">Soporte y mantenimiento TI</option>
                                                            <option value="6">Estrategia de marketing</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-input message-input">
                                                <textarea name="cfMessage" id="message"></textarea>
                                                <label class="cf-label">Escribe tu mensaje <span>*</span></label>
                                            </div>
                                        </div>
                                        <div class="submit-btn">
                                            <button class="tj-primary-btn" type="submit">
                                                <span class="btn-text"><span>Enviar ahora</span></span>
                                                <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="map-area wow fadeInUp" data-wow-delay=".3s">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7969.382414190099!2d-79.012119!3d-2.904986!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91cd1873137aa85b%3A0x8561fed6ebf2199c!2sA.%20Tamariz%20%26%20Avenida%20Remigio%20Crespo%20Toral%2C%20Cuenca!5e0!3m2!1ses!2sec!4v1762491105644!5m2!1ses!2sec"
                                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- start: Footer Section -->
            <?php include 'components/footer.php'; ?>