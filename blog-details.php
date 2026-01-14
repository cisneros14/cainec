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
                                <h1 class="tj-page-title" id="breadcrumb-title">Blog</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span id="breadcrumb-blog">Blog</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Blog Section -->
            <section class="tj-blog-section section-gap slidebar-stickiy-container">
                <div class="container">
                    <div class="row row-gap-5">
                        <div class="col-lg-8">
                            <div class="post-details-wrapper" id="blog-content">
                                <!-- El contenido se cargará dinámicamente -->
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-3 text-muted">Cargando entrada...</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="tj-main-sidebar slidebar-stickiy">
                                <div class="tj-sidebar-widget tj-recent-posts wow fadeInUp !bg-white" data-wow-delay=".3s">
                                    <h4 class="widget-title">Entradas recientes</h4>
                                    <ul id="recent-posts-sidebar">
                                        <!-- Se cargarán dinámicamente -->
                                    </ul>
                                </div>
                                <div class="tj-sidebar-widget widget-categories wow fadeInUp !bg-white" data-wow-delay=".5s">
                                    <h4 class="widget-title">Categorías</h4>
                                    <ul id="categories-sidebar">
                                        <!-- Se cargarán dinámicamente -->
                                    </ul>
                                </div>
                                <div class="tj-sidebar-widget widget-tag-cloud wow fadeInUp !bg-white" data-wow-delay=".7s">
                                    <h4 class="widget-title">Etiquetas</h4>
                                    <nav>
                                        <div class="tagcloud" id="tags-sidebar">
                                            <!-- Se cargarán dinámicamente -->
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Blog Section -->

            <style>
                .blog-images img {
                    width: 100%;
                    height: auto;
                    max-height: 500px;
                    object-fit: cover;
                }

                .post-thumb img {
                    width: 80px;
                    height: 80px;
                    object-fit: cover;
                }
            </style>

            <script>
                const PLACEHOLDER_IMAGE = 'assets/images/recursos/placeholder.webp';
                let allBlogs = [];
                let currentBlog = null;
                let categoriesCount = {};

                // Obtener slug de la URL
                function getSlugFromURL() {
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get('slug');
                }

                // Formatear fecha
                function formatDate(dateString) {
                    if (!dateString) return 'Sin fecha';
                    const date = new Date(dateString);
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return date.toLocaleDateString('es-ES', options);
                }

                function formatFullDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const months = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();
                    return `${day} ${month} ${year}`;
                }

                // Cargar blog por slug
                async function loadBlogBySlug(slug) {
                    try {
                        const response = await fetch('admin/api/blogs/listar.php');
                        const data = await response.json();

                        if (data.success && data.data.length > 0) {
                            allBlogs = data.data.filter(blog => blog.published_at !== null);
                            currentBlog = allBlogs.find(blog => blog.slug === slug);

                            if (currentBlog) {
                                renderBlogContent(currentBlog);
                                loadSidebar();
                            } else {
                                showError('No se encontró la entrada del blog.');
                            }
                        } else {
                            showError('No hay entradas disponibles.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showError('Error al cargar la entrada del blog.');
                    }
                }

                // Renderizar contenido del blog
                function renderBlogContent(blog) {
                    const imageUrl = blog.portada_url || PLACEHOLDER_IMAGE;
                    const publishedDate = formatDate(blog.published_at);
                    const tags = blog.etiquetas ? blog.etiquetas.split(',').map(t => t.trim()) : [];

                    // Actualizar breadcrumb
                    document.getElementById('breadcrumb-title').textContent = blog.titulo;
                    document.getElementById('breadcrumb-blog').textContent = blog.titulo;

                    // Actualizar meta tags para SEO
                    if (blog.meta_descripcion) {
                        let metaDesc = document.querySelector('meta[name="description"]');
                        if (!metaDesc) {
                            metaDesc = document.createElement('meta');
                            metaDesc.name = 'description';
                            document.head.appendChild(metaDesc);
                        }
                        metaDesc.content = blog.meta_descripcion;
                    }

                    document.title = blog.titulo + ' | CAINEC';

                    const content = `
            <div class="blog-images wow fadeInUp" data-wow-delay=".1s">
              <img src="${imageUrl}" alt="${blog.titulo}" onerror="this.src='${PLACEHOLDER_IMAGE}'">
            </div>
            <h2 class="title title-anim">${blog.titulo}</h2>
            <div class="blog-category-two wow fadeInUp" data-wow-delay=".3s">
              ${blog.autor ? `
              <div class="category-item">
                <div class="cate-icons">
                  <i class="tji-user"></i>
                </div>
                <div class="cate-text">
                  <span class="degination">Autor</span>
                  <h6 class="title">${blog.autor}</h6>
                </div>
              </div>
              ` : ''}
              <div class="category-item">
                <div class="cate-icons">
                  <i class="tji-calendar"></i>
                </div>
                <div class="cate-text">
                  <span class="degination">Publicado</span>
                  <h6 class="text">${publishedDate}</h6>
                </div>
              </div>
              ${blog.categoria_nombre ? `
              <div class="category-item">
                <div class="cate-icons">
                  <i class="tji-folder"></i>
                </div>
                <div class="cate-text">
                  <span class="degination">Categoría</span>
                  <h6 class="text">${blog.categoria_nombre}</h6>
                </div>
              </div>
              ` : ''}
            </div>
            <div class="blog-text">
              ${blog.contenido}
            </div>
            ${tags.length > 0 ? `
            <div class="tj-tags-post wow fadeInUp" data-wow-delay=".3s">
              <div class="tagcloud">
                <span>Etiquetas:</span>
                ${tags.map(tag => `<a href="blog-right-sidebar.php?tag=${encodeURIComponent(tag)}">${tag}</a>`).join('')}
              </div>
              <div class="post-share">
                <ul>
                  <li>Compartir:</li>
                  <li><a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(blog.titulo)}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                  <li><a href="https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(window.location.href)}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
                </ul>
              </div>
            </div>
            ` : ''}
            <div class="tj-post__navigation wow fadeInUp" data-wow-delay=".3s">
              <div class="tj-nav__post previous">
                <div class="tj-nav-post__nav prev_post">
                  ${getPreviousBlog() ? `<a href="blog-details.php?slug=${getPreviousBlog().slug}"><span><i class="tji-arrow-left"></i></span>Anterior</a>` : '<span class="text-muted">No hay anterior</span>'}
                </div>
              </div>
              <div class="tj-nav-post__grid">
                <a href="blog-right-sidebar.php"><i class="tji-window"></i></a>
              </div>
              <div class="tj-nav__post next">
                <div class="tj-nav-post__nav next_post">
                  ${getNextBlog() ? `<a href="blog-details.php?slug=${getNextBlog().slug}">Siguiente<span><i class="tji-arrow-right"></i></span></a>` : '<span class="text-muted">No hay siguiente</span>'}
                </div>
              </div>
            </div>
          `;

                    document.getElementById('blog-content').innerHTML = content;
                }

                // Obtener blog anterior
                function getPreviousBlog() {
                    const currentIndex = allBlogs.findIndex(b => b.id === currentBlog.id);
                    return currentIndex > 0 ? allBlogs[currentIndex - 1] : null;
                }

                // Obtener blog siguiente
                function getNextBlog() {
                    const currentIndex = allBlogs.findIndex(b => b.id === currentBlog.id);
                    return currentIndex < allBlogs.length - 1 ? allBlogs[currentIndex + 1] : null;
                }

                // Cargar sidebar
                async function loadSidebar() {
                    // Contar blogs por categoría
                    categoriesCount = {};
                    allBlogs.forEach(blog => {
                        if (blog.id_categoria) {
                            categoriesCount[blog.id_categoria] = (categoriesCount[blog.id_categoria] || 0) + 1;
                        }
                    });

                    loadRecentPosts();
                    loadCategories();
                    loadTags();
                }

                // Cargar posts recientes
                function loadRecentPosts() {
                    const container = document.getElementById('recent-posts-sidebar');
                    const recentPosts = allBlogs.filter(b => b.id !== currentBlog.id).slice(0, 3);

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
                            const container = document.getElementById('categories-sidebar');
                            container.innerHTML = data.data.map(cat => {
                                const count = categoriesCount[cat.id] || 0;
                                return `
                  <li>
                    <a href="blog-right-sidebar.php?categoria=${cat.id}">
                      ${cat.nombre}<span class="number">(${String(count).padStart(2, '0')})</span>
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
                    const container = document.getElementById('tags-sidebar');
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

                    container.innerHTML = Array.from(allTags).slice(0, 10).map(tag =>
                        `<a href="blog-right-sidebar.php?tag=${encodeURIComponent(tag)}">${tag}</a>`
                    ).join('');
                }

                // Mostrar error
                function showError(message) {
                    document.getElementById('blog-content').innerHTML = `
            <div class="text-center py-5">
              <i class="tji-alert-circle" style="font-size: 48px; color: #dc3545;"></i>
              <h3 class="mt-3">${message}</h3>
              <a href="blog-right-sidebar.php" class="tj-primary-btn mt-3">
                <span class="btn-text"><span>Ver todos los blogs</span></span>
                <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
              </a>
            </div>
          `;
                }

                // Inicializar
                document.addEventListener('DOMContentLoaded', function () {
                    const slug = getSlugFromURL();
                    if (slug) {
                        loadBlogBySlug(slug);
                    } else {
                        showError('No se especificó ninguna entrada.');
                    }
                });
            </script>
            <!-- end: Cta Section -->
            <?php include 'components/footer.php'; ?>