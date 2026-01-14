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
                                <h1 class="tj-page-title">Blogs</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Blogs</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Blog Section -->
            <section class="tj-blog-section section-gap">
                <div class="container">
                    <div class="row row-gap-5">
                        <div class="col-lg-8">
                            <div class="row row-gap-4" id="blog-container">
                                <!-- Los blogs se cargarán dinámicamente aquí -->
                            </div>
                            <!-- post pagination -->
                            <div class="tj-pagination d-flex justify-content-center" id="pagination-container">
                                <!-- La paginación se cargará dinámicamente -->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="tj-main-sidebar p-0 slidebar-stickiy">
                                <div class="tj-sidebar-widget widget-search wow fadeInUp !bg-white"
                                    data-wow-delay=".1s">
                                    <h4 class="widget-title">Buscar</h4>
                                    <div class="search-box">
                                        <form onsubmit="event.preventDefault(); searchBlogs();">
                                            <input type="search" name="search" id="searchInput" placeholder="Buscar...">
                                            <button type="submit" value="search">
                                                <i class="tji-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="tj-sidebar-widget tj-recent-posts wow fadeInUp !bg-white"
                                    data-wow-delay=".3s">
                                    <h4 class="widget-title">Entradas recientes</h4>
                                    <ul id="recent-posts-container">
                                        <!-- Las entradas recientes se cargarán dinámicamente aquí -->
                                    </ul>
                                </div>
                                <div class="tj-sidebar-widget widget-categories wow fadeInUp !bg-white"
                                    data-wow-delay=".5s">
                                    <h4 class="widget-title">Categorías</h4>
                                    <ul id="categories-container">
                                        <!-- Las categorías se cargarán dinámicamente aquí -->
                                    </ul>
                                </div>
                                <div class="tj-sidebar-widget widget-tag-cloud wow fadeInUp !bg-white"
                                    data-wow-delay=".7s">
                                    <h4 class="widget-title">Etiquetas</h4>
                                    <nav>
                                        <div class="tagcloud" id="tags-container">
                                            <!-- Las etiquetas se cargarán dinámicamente aquí -->
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- end: Blog Section -->


            <!-- end: Cta Section -->

            <!-- start: Footer Section -->
            <?php include 'components/footer.php'; ?>

            <!-- Estilos para imágenes con altura uniforme -->
            <style>
                /* Contenedor de imagen principal con aspecto 1:1 */
                .blog-thumb {
                    position: relative;
                    width: 100%;
                    padding-bottom: 100%;
                    /* Ratio 1:1 (cuadrado) */
                    overflow: hidden;
                }

                .blog-thumb a {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }

                .blog-thumb img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                }

                /* Fecha sobre la imagen */
                .blog-thumb .blog-date {
                    position: absolute;
                    z-index: 10;
                }

                /* Imágenes del sidebar - mantener cuadradas pero más pequeñas */
                .post-thumb img {
                    width: 80px;
                    height: 80px;
                    object-fit: cover;
                    object-position: center;
                }
            </style>

            <!-- Script para cargar blogs dinámicamente -->
            <script>
                // Configuración
                const ITEMS_PER_PAGE = 6;
                const PLACEHOLDER_IMAGE = 'assets/images/recursos/placeholder.webp';
                let allBlogs = [];
                let filteredBlogs = [];
                let currentPage = 1;
                let categoriesCount = {};
                let currentFilters = {
                    search: '',
                    categoria: '',
                    tag: ''
                };

                // Función para formatear fecha
                function formatDate(dateString) {
                    if (!dateString) return { date: '', month: '' };
                    const date = new Date(dateString);
                    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    return {
                        date: date.getDate(),
                        month: months[date.getMonth()]
                    };
                }

                // Función para formatear fecha completa
                function formatFullDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const months = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();
                    return `${day} ${month} ${year}`;
                }

                // Función para truncar texto
                function truncateText(text, maxLength) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.innerHTML = text;
                    const textContent = div.textContent || div.innerText || '';
                    if (textContent.length <= maxLength) return textContent;
                    return textContent.substr(0, maxLength) + '...';
                }

                // Cargar blogs
                async function loadBlogs() {
                    try {
                        const response = await fetch('admin/api/blogs/listar.php');
                        const data = await response.json();

                        if (data.success && data.data.length > 0) {
                            // Filtrar solo entradas de tipo 'blog' y publicadas
                            allBlogs = data.data.filter(blog => blog.published_at !== null && blog.tipo === 'blog');
                            // Asegurar orden descendente por fecha publicada (más recientes primero)
                            allBlogs.sort((a, b) => new Date(b.published_at) - new Date(a.published_at));
                            filteredBlogs = [...allBlogs];

                            // Contar blogs por categoría
                            categoriesCount = {};
                            allBlogs.forEach(blog => {
                                if (blog.id_categoria) {
                                    categoriesCount[blog.id_categoria] = (categoriesCount[blog.id_categoria] || 0) + 1;
                                }
                            });

                            // Leer filtros de URL
                            readFiltersFromURL();
                            applyFilters();
                            loadRecentPosts();
                            loadTags();
                        } else {
                            document.getElementById('blog-container').innerHTML = `
              <div class="col-12 text-center py-5">
                <p class="text-muted">No hay entradas publicadas en este momento.</p>
              </div>
            `;
                        }
                    } catch (error) {
                        console.error('Error al cargar blogs:', error);
                        document.getElementById('blog-container').innerHTML = `
            <div class="col-12 text-center py-5">
              <p class="text-danger">Error al cargar las entradas del blog.</p>
            </div>
          `;
                    }
                }

                // Leer filtros de la URL
                function readFiltersFromURL() {
                    const urlParams = new URLSearchParams(window.location.search);
                    currentFilters.search = urlParams.get('search') || '';
                    currentFilters.categoria = urlParams.get('categoria') || '';
                    currentFilters.tag = urlParams.get('tag') || '';

                    // Actualizar input de búsqueda
                    if (currentFilters.search) {
                        document.getElementById('searchInput').value = currentFilters.search;
                    }
                }

                // Aplicar filtros
                function applyFilters() {
                    filteredBlogs = allBlogs.filter(blog => {
                        // Filtro de búsqueda
                        if (currentFilters.search) {
                            const searchTerm = currentFilters.search.toLowerCase();
                            const titleMatch = blog.titulo.toLowerCase().includes(searchTerm);
                            const contentMatch = blog.contenido.toLowerCase().includes(searchTerm);
                            const authorMatch = blog.autor && blog.autor.toLowerCase().includes(searchTerm);

                            if (!titleMatch && !contentMatch && !authorMatch) {
                                return false;
                            }
                        }

                        // Filtro de categoría
                        if (currentFilters.categoria && blog.id_categoria != currentFilters.categoria) {
                            return false;
                        }

                        // Filtro de etiqueta
                        if (currentFilters.tag) {
                            if (!blog.etiquetas || !blog.etiquetas.toLowerCase().includes(currentFilters.tag.toLowerCase())) {
                                return false;
                            }
                        }

                        return true;
                    });

                    currentPage = 1;
                    renderBlogs();
                }

                // Búsqueda
                function searchBlogs() {
                    const searchTerm = document.getElementById('searchInput').value;
                    currentFilters.search = searchTerm;
                    updateURL();
                    applyFilters();
                }

                // Filtrar por categoría
                function filterByCategory(categoriaId) {
                    currentFilters.categoria = categoriaId;
                    currentFilters.search = '';
                    currentFilters.tag = '';
                    document.getElementById('searchInput').value = '';
                    updateURL();
                    applyFilters();
                }

                // Filtrar por etiqueta
                function filterByTag(tag) {
                    currentFilters.tag = tag;
                    currentFilters.search = '';
                    currentFilters.categoria = '';
                    document.getElementById('searchInput').value = '';
                    updateURL();
                    applyFilters();
                }

                // Actualizar URL
                function updateURL() {
                    const params = new URLSearchParams();
                    if (currentFilters.search) params.set('search', currentFilters.search);
                    if (currentFilters.categoria) params.set('categoria', currentFilters.categoria);
                    if (currentFilters.tag) params.set('tag', currentFilters.tag);

                    const newURL = params.toString() ?
                        `${window.location.pathname}?${params.toString()}` :
                        window.location.pathname;

                    window.history.pushState({}, '', newURL);
                }

                // Renderizar blogs
                function renderBlogs() {
                    const container = document.getElementById('blog-container');
                    const start = (currentPage - 1) * ITEMS_PER_PAGE;
                    const end = start + ITEMS_PER_PAGE;
                    const blogsToShow = filteredBlogs.slice(start, end);

                    if (blogsToShow.length === 0) {
                        let message = 'No hay entradas para mostrar.';
                        if (currentFilters.search) {
                            message = `No se encontraron resultados para "${currentFilters.search}".`;
                        } else if (currentFilters.categoria) {
                            message = 'No hay entradas en esta categoría.';
                        } else if (currentFilters.tag) {
                            message = `No hay entradas con la etiqueta "${currentFilters.tag}".`;
                        }

                        container.innerHTML = `
                            <div class="col-12 text-center py-5">
                            <p class="text-muted">${message}</p>
                            ${(currentFilters.search || currentFilters.categoria || currentFilters.tag) ?
                                                '<button class="tj-primary-btn mt-3" onclick="clearFilters()"><span class="btn-text"><span>Ver todas las entradas</span></span></button>' :
                                                ''}
                            </div>
                        `;
                        document.getElementById('pagination-container').innerHTML = '';
                        return;
                    }

                    container.innerHTML = blogsToShow.map((blog, index) => {
                        const { date, month } = formatDate(blog.published_at);
                        const imageUrl = blog.portada_url || PLACEHOLDER_IMAGE;
                        const delay = (index % 2 === 0) ? '.1s' : '.3s';

                        return `
            <div class="col-md-6">
              <div class="blog-item wow fadeInUp" data-wow-delay="${delay}">
                <div class="blog-thumb">
                  <a href="blog-details.php?slug=${blog.slug}">
                    <img src="${imageUrl}" alt="${blog.titulo}" onerror="this.src='${PLACEHOLDER_IMAGE}'">
                  </a>
                  ${date ? `
                    <div class="blog-date">
                      <span class="date">${date}</span>
                      <span class="month">${month}</span>
                    </div>
                  ` : ''}
                </div>
                <div class="blog-content">
                  <div class="blog-meta">
                    <span class="categories">
                      <a href="#" onclick="filterByCategory('${blog.id_categoria}'); return false;">
                        ${blog.categoria_nombre || 'Sin categoría'}
                      </a>
                    </span>
                    ${blog.autor ? `<span>Por <a href="#">${blog.autor}</a></span>` : ''}
                  </div>
                  <h4 class="title">
                    <a href="blog-details.php?slug=${blog.slug}">${blog.titulo}</a>
                  </h4>
                  <a class="text-btn" href="blog-details.php?slug=${blog.slug}">
                    <span class="btn-text"><span>Leer más</span></span>
                    <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                  </a>
                </div>
              </div>
            </div>
          `;
                    }).join('');

                    renderPagination();
                }

                // Limpiar filtros
                function clearFilters() {
                    currentFilters = { search: '', categoria: '', tag: '' };
                    document.getElementById('searchInput').value = '';
                    window.history.pushState({}, '', window.location.pathname);
                    applyFilters();
                }

                // Renderizar paginación
                function renderPagination() {
                    const container = document.getElementById('pagination-container');
                    const totalPages = Math.ceil(filteredBlogs.length / ITEMS_PER_PAGE);

                    if (totalPages <= 1) {
                        container.innerHTML = '';
                        return;
                    }

                    let paginationHTML = '<ul>';

                    for (let i = 1; i <= totalPages; i++) {
                        if (i === currentPage) {
                            paginationHTML += `<li><span aria-current="page" class="page-numbers current">${String(i).padStart(2, '0')}</span></li>`;
                        } else {
                            paginationHTML += `<li><a class="page-numbers" href="#" onclick="changePage(${i}); return false;">${String(i).padStart(2, '0')}</a></li>`;
                        }
                    }

                    if (currentPage < totalPages) {
                        paginationHTML += `<li><a class="next page-numbers" href="#" onclick="changePage(${currentPage + 1}); return false;"><i class="tji-arrow-right-long"></i></a></li>`;
                    }

                    paginationHTML += '</ul>';
                    container.innerHTML = paginationHTML;
                }

                // Cambiar página
                function changePage(page) {
                    currentPage = page;
                    renderBlogs();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                // Cargar posts recientes
                function loadRecentPosts() {
                    const container = document.getElementById('recent-posts-container');
                    const recentPosts = allBlogs.slice(0, 3);

                    if (recentPosts.length === 0) {
                        container.innerHTML = '<li class="text-muted">No hay entradas recientes.</li>';
                        return;
                    }

                    container.innerHTML = recentPosts.map(blog => {
                        const imageUrl = blog.portada_url || PLACEHOLDER_IMAGE;
                        const dateFormatted = formatFullDate(blog.published_at);

                        return `
            <li>
              <div class="post-thumb">
                <a href="blog-details.php?slug=${blog.slug}">
                  <img src="${imageUrl}" alt="${blog.titulo}" onerror="this.src='${PLACEHOLDER_IMAGE}'">
                </a>
              </div>
              <div class="post-content">
                <h6 class="post-title">
                  <a href="blog-details.php?slug=${blog.slug}">${blog.titulo}</a>
                </h6>
                <div class="blog-meta">
                  <ul>
                    <li>${dateFormatted}</li>
                  </ul>
                </div>
              </div>
            </li>
          `;
                    }).join('');
                }

                // Cargar categorías
                async function loadCategories() {
                    try {
                        const response = await fetch('admin/api/categorias/listar.php');
                        const data = await response.json();

                                if (data.success && data.data.length > 0) {
                                                        const container = document.getElementById('categories-container');

                                                        // Filtrar solo categorías que tengan al menos 1 blog en el conjunto ya filtrado
                                                        const catsWithCount = data.data
                                                                .map(cat => ({ ...cat, count: categoriesCount[cat.id] || 0 }))
                                                                .filter(cat => cat.count > 0)
                                                                .sort((a, b) => b.count - a.count);

                                                        if (catsWithCount.length === 0) {
                                                                container.innerHTML = '<li class="text-muted">No hay categorías disponibles.</li>';
                                                                return;
                                                        }

                                                        container.innerHTML = catsWithCount.map(cat => {
                                                                const isActive = currentFilters.categoria == cat.id;
                                                                return `
                                <li>
                                    <a href="#" onclick="filterByCategory('${cat.id}'); return false;" ${isActive ? 'style="font-weight: bold; color: var(--tj-color-theme-primary);"' : ''}>
                                        ${cat.nombre}<span class="number">(${String(cat.count).padStart(2, '0')})</span>
                                    </a>
                                </li>
                            `;
                                                        }).join('');
                                                }
                    } catch (error) {
                        console.error('Error al cargar categorías:', error);
                    }
                }

                // Cargar etiquetas
                function loadTags() {
                    const container = document.getElementById('tags-container');
                    const allTags = new Set();

                    allBlogs.forEach(blog => {
                        if (blog.etiquetas) {
                            const tags = blog.etiquetas.split(',').map(tag => tag.trim());
                            tags.forEach(tag => {
                                if (tag) allTags.add(tag);
                            });
                        }
                    });

                    if (allTags.size === 0) {
                        container.innerHTML = '<p class="text-muted">No hay etiquetas disponibles.</p>';
                        return;
                    }

                    container.innerHTML = Array.from(allTags).slice(0, 10).map(tag => {
                        const isActive = currentFilters.tag === tag;
                        return `<a href="#" onclick="filterByTag('${tag.replace(/'/g, "\\'")}'); return false;" ${isActive ? 'style="font-weight: bold; background-color: var(--tj-color-theme-primary); color: white;"' : ''}>${tag}</a>`;
                    }).join('');
                }

                // Cargar todo al iniciar
                document.addEventListener('DOMContentLoaded', async function () {
                    await loadBlogs();
                    await loadCategories();
                });
            </script>