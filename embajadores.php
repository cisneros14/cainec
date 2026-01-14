<?php
include 'components/header.php';
?>
<!-- end: Header Area -->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">
            <div class="space-for-header"></div>
            <!-- start: Breadcrumb Section -->
            <?php
            $bgImages = [
                'assets/images/bg/img1.webp',
                'assets/images/bg/img2.webp',
                'assets/images/bg/img3.webp',
            ];
            $bg = $bgImages[array_rand($bgImages)];
            ?>
            <section class="tj-page-header section-gap-x" data-bg-image="<?= $bg ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tj-page-header-content text-center">
                                <h1 class="tj-page-title">Embajadores CAINEC</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Embajadores</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Team Section -->
            <section class="tj-team-section section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i
                                        class="tji-box"></i>Nuestros Representantes</span>
                                <h2 class="sec-title title-anim">Embajadores <span>CAINEC</span></h2>
                                <p class="desc wow fadeInUp" data-wow-delay=".3s">
                                    Líderes que representan nuestros valores y promueven el desarrollo inmobiliario en
                                    cada rincón del Ecuador.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="embajadores-container">
                        <!-- Los embajadores se cargarán dinámicamente aquí -->
                    </div>
                </div>
            </section>
            <!-- end: Team Section -->

            <?php include 'components/cta-3.php'; ?>

        </main>

        <!-- Modal Detalle Embajador (Idéntico a Junta Directiva) -->
        <dialog id="modalEmbajador"
            class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto bg-white">
            <div class="!p-6 md:!p-14">
                <!-- Contenido -->
                <div class="px-8 pb-8 -mt-16 relative">
                    <!-- Foto de perfil -->
                    <div class="flex flex-col sm:flex-row !items-center gap-6 !mb-6 md:!mb-10 pt-16">
                        <div
                            class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0">
                            <img id="modal-img" src="" alt="" class="w-full h-full object-cover object-top">
                            <div id="modal-placeholder"
                                class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 hidden">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 text-center sm:text-left">
                            <h2 id="modal-nombre" class="!text-2xl font-bold text-gray-900 mb-1"></h2>
                            <p id="modal-rol" class="text-[var(--tj-color-theme-primary)] font-medium text-lg mb-1">
                            </p>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="!mb-6 md:!mb-10">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Sobre mí</h3>
                        <p id="modal-descripcion" class="text-gray-600 leading-relaxed whitespace-pre-line"></p>
                        <p id="modal-no-descripcion" class="text-gray-400 italic hidden">Sin descripción disponible.
                        </p>
                    </div>

                    <!-- Contacto -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Correo</p>
                                <a id="modal-correo" href=""
                                    class="text-gray-900 hover:text-blue-600 font-medium break-all"></a>
                            </div>
                        </div>

                        <div id="modal-telefono-container" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <div
                                class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Teléfono</p>
                                <a id="modal-telefono" href=""
                                    class="text-gray-900 hover:text-green-600 font-medium"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </dialog>

        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>

        <script>
            document.addEventListener('DOMContentLoaded', async function () {
                await loadEmbajadores();
            });

            async function loadEmbajadores() {
                try {
                    const response = await fetch('admin/api/embajadores/listar.php?v=' + new Date().getTime());
                    const data = await response.json();

                    if (data.success) {
                        renderEmbajadores(data.data);
                    }
                } catch (error) {
                    console.error('Error al cargar embajadores:', error);
                }
            }

            function renderEmbajadores(embajadores) {
                const container = document.getElementById('embajadores-container');

                if (embajadores.length === 0) {
                    container.innerHTML = '<div class="col-12 text-center"><p>No hay embajadores registrados.</p></div>';
                    return;
                }

                const colClass = embajadores.length <= 3 ? 'col-lg-4' : 'col-lg-3';

                container.innerHTML = embajadores.map((emb, index) => `
                    <div class="${colClass} col-sm-6">
                        <div class="team-item wow fadeInUp cursor-pointer"
                            data-wow-delay=".${(index % 4 + 1)}s"
                            onclick='openEmbajadorModal(${JSON.stringify(emb).replace(/'/g, "&apos;")})'>
                            <div class="team-img">
                                <div class="team-img-inner">
                                    ${emb.url_img
                        ? `<img src="${escapeHtml(emb.url_img)}" alt="${escapeHtml(emb.nombre)} ${escapeHtml(emb.apellido)}" class="w-full aspect-square object-cover object-top">`
                        : `<div class="placeholder-img w-full aspect-square flex items-center justify-center text-white text-5xl font-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            ${escapeHtml(emb.nombre.charAt(0))}${escapeHtml(emb.apellido.charAt(0))}
                                           </div>`
                    }
                                </div>
                                <div class="social-links">
                                    <ul>
                                        <li><a href="#" onclick="event.stopPropagation();"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="team-content">
                                <h4 class="title">${escapeHtml(emb.nombre)} ${escapeHtml(emb.apellido)}</h4>
                                <span class="designation">${escapeHtml(emb.categoria || '')}</span>
                                <a class="mail-at" href="mailto:${escapeHtml(emb.correo)}" onclick="event.stopPropagation();"><i class="tji-at"></i></a>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                    ${escapeHtml(emb.descripcion || '')}
                                </p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            function openEmbajadorModal(emb) {
                // Llenar datos
                document.getElementById('modal-nombre').textContent = `${emb.nombre} ${emb.apellido}`;
                document.getElementById('modal-rol').textContent = emb.categoria || '';

                // Imagen
                const img = document.getElementById('modal-img');
                const placeholder = document.getElementById('modal-placeholder');

                if (emb.url_img) {
                    img.src = emb.url_img;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }

                // Descripción
                const descEl = document.getElementById('modal-descripcion');
                const noDescEl = document.getElementById('modal-no-descripcion');

                if (emb.descripcion) {
                    descEl.textContent = emb.descripcion;
                    descEl.classList.remove('hidden');
                    noDescEl.classList.add('hidden');
                } else {
                    descEl.classList.add('hidden');
                    noDescEl.classList.remove('hidden');
                }

                // Contacto
                const correoLink = document.getElementById('modal-correo');
                correoLink.href = `mailto:${emb.correo}`;
                correoLink.textContent = emb.correo;

                const telContainer = document.getElementById('modal-telefono-container');
                const telLink = document.getElementById('modal-telefono');

                if (emb.telefono) {
                    telLink.href = `tel:${emb.telefono}`;
                    telLink.textContent = emb.telefono;
                    telContainer.classList.remove('hidden');
                } else {
                    telContainer.classList.add('hidden');
                }

                // Abrir modal
                document.getElementById('modalEmbajador').showModal();
            }

            // Cerrar modal al hacer clic fuera (backdrop)
            document.getElementById('modalEmbajador').addEventListener('click', function (event) {
                if (event.target === this) {
                    this.close();
                }
            });

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        </script>