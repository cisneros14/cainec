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
                                <h1 class="tj-page-title">Junta Directiva</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Junta Directiva</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <h1 class="text-3xl font-bold text-center !mt-6 md:!mt-20 max-w-4xl mx-auto" >Conoce a los expertos que hacen parte de la junta directiva CAINEC</h1>

            <!-- Secciones dinámicas por categoría -->
            <div id="junta-sections-container">
                <!-- Las secciones se cargarán dinámicamente -->
            </div>

            <?php include 'components/cta-3.php'; ?>

            <!-- Modal Detalle Miembro -->
        </main>

        <!-- Modal Detalle Miembro -->
        <dialog id="modalMiembro"
            class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-2xl fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh] overflow-y-auto bg-white">
            <div class="!p-6 md:!p-14">
                <!-- Header con imagen de fondo o color -->
                <!-- <div class="h-14 bg-[var(--tj-color-theme-primary)] relative">
                    <button onclick="document.getElementById('modalMiembro').close()"
                        class="absolute top-4 right-4 text-white hover:bg-white/20 rounded-full p-2 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div> -->

                <!-- Contenido -->
                <div class="px-8 pb-8 -mt-16 relative">
                    <!-- Foto de perfil -->
                    <div class="flex flex-col sm:flex-row !items-center gap-6 !mb-6 md:!mb-10">
                        <div
                            class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0">
                            <img id="modal-img" src="" alt="" class="w-full h-full object-cover">
                            <div id="modal-placeholder"
                                class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 hidden">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1">
                            <h2 id="modal-nombre" class="!text-2xl font-bold text-gray-900 mb-1"></h2>
                            <p id="modal-rol" class="text-[var(--tj-color-theme-primary)] font-medium text-lg mb-1">
                            </p>
                            <span id="modal-categoria"
                                class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full"></span>
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

        </main>

        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>

        <script>
            // Cargar miembros y categorías al iniciar
            document.addEventListener('DOMContentLoaded', async function () {
                await loadJuntaDirectiva();
            });

            async function loadJuntaDirectiva() {
                try {
                    // Cargar miembros (con timestamp para evitar caché)
                    const miembrosResponse = await fetch('admin/api/juntaDirectiva/listar.php?v=' + new Date().getTime());
                    const miembrosData = await miembrosResponse.json();

                    // Cargar categorías
                    const categoriasResponse = await fetch('admin/api/categoriasJunta/listar.php?v=' + new Date().getTime());
                    const categoriasData = await categoriasResponse.json();

                    if (miembrosData.success && categoriasData.success) {
                        renderJuntaSections(miembrosData.data, categoriasData.data);
                    }
                } catch (error) {
                    console.error('Error al cargar junta directiva:', error);
                }
            }

            function renderJuntaSections(miembros, categorias) {
                const container = document.getElementById('junta-sections-container');

                // Agrupar miembros por categoría
                const miembrosPorCategoria = {};
                categorias.forEach(cat => {
                    miembrosPorCategoria[cat.nombre] = [];
                });

                miembros.forEach(miembro => {
                    if (miembrosPorCategoria[miembro.categoria]) {
                        miembrosPorCategoria[miembro.categoria].push(miembro);
                    }
                });

                // Renderizar cada categoría como una sección
                let html = '';
                categorias.forEach(categoria => {
                    const miembrosCategoria = miembrosPorCategoria[categoria.nombre];

                    if (miembrosCategoria && miembrosCategoria.length > 0) {
                        html += `
                <section class="tj-team-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="sec-heading text-center">
                                    <span class="sub-title wow fadeInUp" data-wow-delay=".1s">
                                        <i class="tji-box"></i>${escapeHtml(categoria.nombre)}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            ${miembrosCategoria.map((miembro, index) => `
                                <div class="col-lg-3 col-sm-6">
                                    <div class="team-item wow fadeInUp cursor-pointer transition-all duration-300" 
                                         data-wow-delay=".${(index % 4 + 1)}s"
                                         onclick='openMemberModal(${JSON.stringify(miembro).replace(/'/g, "&apos;")})'>
                                        <div class="team-img">
                                            <div class="team-img-inner">
                                                ${miembro.url_img
                                ? `<img src="${escapeHtml(miembro.url_img)}" alt="${escapeHtml(miembro.nombre)} ${escapeHtml(miembro.apellido)}" style="height: 300px; width: 100%; object-fit: cover; object-position: top;">`
                                : `<div class="placeholder-img" style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold;">
                                                        ${escapeHtml(miembro.nombre.charAt(0))}${escapeHtml(miembro.apellido.charAt(0))}
                                                    </div>`
                            }
                                            </div>
                                        </div>
                                        <div class="team-content">
                                            <h4 class="title">${escapeHtml(miembro.nombre)} ${escapeHtml(miembro.apellido)}</h4>
                                            <span class="designation">${escapeHtml(miembro.rol)}</span>
                                            <span class="text-sm text-[var(--tj-color-theme-primary)] mt-2 block font-medium">Ver perfil completo <i class="tji-arrow-right ml-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </section>
            `;
                    }
                });

                container.innerHTML = html;
            }

            function openMemberModal(miembro) {
                // Llenar datos
                document.getElementById('modal-nombre').textContent = `${miembro.nombre} ${miembro.apellido}`;
                document.getElementById('modal-rol').textContent = miembro.rol;
                document.getElementById('modal-categoria').textContent = miembro.categoria;

                // Imagen
                const img = document.getElementById('modal-img');
                const placeholder = document.getElementById('modal-placeholder');

                if (miembro.url_img) {
                    img.src = miembro.url_img;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }

                // Descripción
                const descEl = document.getElementById('modal-descripcion');
                const noDescEl = document.getElementById('modal-no-descripcion');

                if (miembro.descripcion) {
                    descEl.textContent = miembro.descripcion;
                    descEl.classList.remove('hidden');
                    noDescEl.classList.add('hidden');
                } else {
                    descEl.classList.add('hidden');
                    noDescEl.classList.remove('hidden');
                }

                // Contacto
                const correoLink = document.getElementById('modal-correo');
                correoLink.href = `mailto:${miembro.correo}`;
                correoLink.textContent = miembro.correo;

                const telContainer = document.getElementById('modal-telefono-container');
                const telLink = document.getElementById('modal-telefono');

                if (miembro.telefono) {
                    telLink.href = `tel:${miembro.telefono}`;
                    telLink.textContent = miembro.telefono;
                    telContainer.classList.remove('hidden');
                } else {
                    telContainer.classList.add('hidden');
                }

                // Abrir modal
                document.getElementById('modalMiembro').showModal();
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        </script>