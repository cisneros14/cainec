<section id="choose2" class="tj-choose-section section-gap">
    <div class="container flex flex-col items-center">
        <div class="row">
            <div class="col-12">
                <div class="sec-heading text-center">
                    <span class="sub-title wow fadeInUp" data-wow-delay=".3s"><i class="tji-box"></i>Únete como socio
                        CAINEC</span>
                    <h2 class="sec-title title-anim">Beneficios de ser socio CAINEC</h2>
                    <p>Y goza de la variedad de beneficios que ofrecemos</p>
                </div>
            </div>
        </div>

        <div class="row row-gap-4 rightSwipeWrap">
            <?php
            $beneficios = [
                [
                    'title' => 'Networking y Alianzas',
                    'text' => 'Con presencia en las principales ciudades del país, CAINEC facilita oportunidades de negocio, eventos comerciales y convenios nacionales e internacionales que fortalecen la red inmobiliaria.'
                ],
                [
                    'title' => 'Formación y Crecimiento Profesional',
                    'text' => 'Descuentos en programas académicos, diplomados y capacitaciones gracias a alianzas con universidades y la Escuela de Negocios CAINEC, además de acceso a mentorías y certificaciones.'
                ],
                [
                    'title' => 'Eventos y Ferias',
                    'text' => 'Participación preferencial y descuentos en congresos, ferias y encuentros sectoriales que impulsan la visibilidad y el intercambio profesional.'
                ],
                [
                    'title' => 'Posicionamiento y Difusión',
                    'text' => 'Promoción en la revista Entorno Inmobiliario, acceso a reportes del sector, uso de marca institucional y difusión en canales oficiales y directorios nacionales.'
                ],
                [
                    'title' => 'Innovación y Tecnología',
                    'text' => 'Acceso a herramientas digitales, CRM inmobiliarios con descuentos y proyectos tecnológicos que fortalecen la competitividad del gremio.'
                ],
                [
                    'title' => 'Más beneficios',
                    'text' => 'Accede a beneficios exclusivos y novedades periódicas diseñadas para impulsar tu negocio y profesionalización.'
                ]
            ];

            foreach ($beneficios as $b): ?>
                <div class="col-lg-4">
                    <div class="choose-box right-swipe">
                        <div class="choose-content">
                            <h4 class="title"><?= htmlspecialchars($b['title']) ?></h4>
                            <p class="desc"><?= htmlspecialchars($b['text']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="btn-wrap wow fadeInUp mt-3 md:mt-5" data-wow-delay=".6s">
            <a class="tj-primary-btn" href="register.php">
                <span class="btn-text"><span>Unirme como socio CAINEC</span></span>
                <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
            </a>
        </div> 
    </div>
</section>
<!-- start: Cta Section -->
<section class="tj-cta-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cta-area flex items-start justify-between !p-5 md:!p-10">
                    <div class="cta-content p-0 mb-5 md:!mb-0">
                        <h2 class="title title-anim">Construyamos el futuro juntos.</h2>
                        <div class="cta-btn wow fadeInUp" data-wow-delay=".6s">
                            <a class="tj-primary-btn btn-dark" href="register.php">
                                <span class="btn-text"><span>Comienza ahora</span></span>
                                <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="cta-content p-0">
                        <div class="footer-widget widget-subscribe wow fadeInUp mt-0" data-wow-delay=".7s">
                            <h3 class="title text-white !text-3xl">Suscríbete a nuestro boletín.</h3>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="email" name="email" placeholder="Introduce tu correo electrónico">
                                    <button type="submit"><i class="tji-plane"></i></button>
                                    <label for="agree" class="text-white">
                                        <input id="agree" type="checkbox" class="border-white">
                                        Acepto los <a href="#" class="text-white">Términos
                                            y
                                            Condiciones</a></label>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- end: Cta Section -->
</main>
<footer class="tj-footer-section footer-1 section-gap-x">
    <div class="footer-main-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="footer-widget wow fadeInUp" data-wow-delay=".1s">
                        <div class="footer-logo">
                            <a href="index.php">
                                <img src="assets/images/logos/logo.webp"
                                    class="px-2 bg-[var(--tj-color-theme-primary)] rounded overflow-hidden" alt="Logos">
                            </a>
                        </div>
                        <div class="footer-text">
                            <p>Desarrollamos y personalizamos los recorridos de nuestros clientes para
                                aumentar la satisfacción y la fidelidad.</p>
                        </div>

                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="footer-widget widget-nav-menu wow fadeInUp" data-wow-delay=".3s">
                        <h5 class="title">Servicios</h5>
                        <ul>
                            <li><a href="#">Experiencia del cliente</a></li>
                            <li><a href="#">Programas de formación</a></li>
                            <li><a href="#">Estrategia empresarial</a></li>
                            <li><a href="#">Programa de formación</a></li>
                            <li><a href="#">Consultoría ESG</a></li>
                            <li><a href="#">Centro de desarrollo</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="footer-widget widget-nav-menu wow fadeInUp" data-wow-delay=".5s">
                        <h5 class="title">Recursos</h5>
                        <ul>
                            <li><a href="#">Contáctanos</a></li>
                            <li><a href="#">Equipo</a></li>
                            <li><a href="#">Reconocimientos</a></li>
                            <li><a href="careers.html">Carreras <span class="badge">Nuevo</span></a></li>
                            <li><a href="#">Noticias</a></li>
                            <li><a href="#">Comentarios</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="tj-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="copyright-content-area">
                        <div class="footer-contact">
                            <ul>
                                <li>
                                    <a href="tel:10095447818">
                                        <span class="icon"><i class="tji-phone-2"></i></span>
                                        <span class="text">+1 (009) 544-7818</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:info@bexon.com">
                                        <span class="icon"><i class="tji-envelop-2"></i></span>
                                        <span class="text">info@cainec.com</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="social-links">
                            <ul>
                                <li><a href="https://www.facebook.com/" target="_blank"><i
                                            class="fa-brands fa-facebook-f"></i></a>
                                </li>
                                <li><a href="https://www.instagram.com/" target="_blank"><i
                                            class="fa-brands fa-instagram"></i></a>
                                </li>
                                <li><a href="https://x.com/" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                                </li>
                                <li><a href="https://www.linkedin.com/" target="_blank"><i
                                            class="fa-brands fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="copyright-text">
                            <p>&copy; 2025&nbsp;<a href="https://themeforest.net/user/theme-junction/portfolio"
                                    target="_blank">CAINEC</a>. Todos los derechos reservados.</p>
                        </div>
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
</footer>
<!-- end: Footer Section -->
</div>
</div>
<!-- JS here -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/gsap.min.js"></script>
<script src="assets/js/ScrollSmoother.js"></script>
<script src="assets/js/gsap-scroll-to-plugin.min.js"></script>
<script src="assets/js/gsap-scroll-trigger.min.js"></script>
<script src="assets/js/gsap-split-text.min.js"></script>
<script src="assets/js/jquery.nice-select.min.js"></script>
<script src="assets/js/swiper.min.js"></script>
<script src="assets/js/odometer.min.js"></script>
<script src="assets/js/venobox.min.js"></script>
<script src="assets/js/appear.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/meanmenu.js"></script>
<script src="assets/js/main.js"></script>


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
</body>

</html>