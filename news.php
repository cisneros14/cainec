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
                <h1 class="tj-page-title">Ultimas Noticias Cainec</h1>
                <div class="tj-page-link">
                  <span><i class="tji-home text-white"></i></span>
                  <span>
                    <a href="index.php">Inicio</a>
                  </span>
                  <span><i class="tji-arrow-right"></i></span>
                  <span>
                    <span>Noticias</span>
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
          <div class="row row-gap-4">
            <?php
            // Obtener noticias desde la base de datos (solo tipo = 'noticia') con paginación
            $noticias = [];
            $PLACEHOLDER = 'assets/images/recursos/placeholder.webp';

            // Paginación
            $perPage = 6; // mantener tamaño similar a diseño original
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($page - 1) * $perPage;
            $totalNoticias = 0;
            $totalPages = 1;

            if (isset($pdo) && $pdo !== null) {
              try {
                // contar totales
                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE tipo = 'noticia'");
                $countStmt->execute();
                $totalNoticias = (int)$countStmt->fetchColumn();

                if ($totalNoticias > 0) {
                  $totalPages = (int)ceil($totalNoticias / $perPage);

                  $stmt = $pdo->prepare(
                    "SELECT b.id, b.titulo, b.slug, COALESCE(b.portada_url, '') AS portada_url, b.published_at, b.autor, cb.nombre AS categoria_nombre, b.created_at
                     FROM blogs b
                     LEFT JOIN categoria_blog cb ON b.id_categoria = cb.id
                     WHERE b.tipo = 'noticia'
                     ORDER BY COALESCE(b.published_at, b.created_at) DESC
                     LIMIT :limit OFFSET :offset"
                  );
                  $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
                  $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
                  $stmt->execute();
                  $noticias = $stmt->fetchAll();
                }
              } catch (PDOException $e) {
                $noticias = [];
              }
            }

            if (empty($noticias)) {
                // Mostrar una única tarjeta informativa si no hay noticias (no romper la UX)
                ?>
                <div class="col-12">
                  <div class="blog-item wow fadeInUp" data-wow-delay=".1s">
                    <div class="blog-thumb">
                      <a href="#"><img src="<?= $PLACEHOLDER ?>" alt=""></a>
                      <div class="blog-date">
                        <span class="date"><?= date('d') ?></span>
                        <span class="month"><?= date('M') ?></span>
                      </div>
                    </div>
                    <div class="blog-content">
                      <div class="blog-meta">
                        <span class="categories"><a href="#">Noticias</a></span>
                      </div>
                      <h4 class="title">No hay noticias disponibles</h4>
                      <p class="text-muted">Vuelve pronto para ver las últimas noticias.</p>
                    </div>
                  </div>
                </div>
                <?php
            } else {
                foreach ($noticias as $news) {
                    $image = !empty($news['portada_url']) ? $news['portada_url'] : $PLACEHOLDER;
                    $dateSource = $news['published_at'] ?: $news['created_at'];
                    $day = $dateSource ? date('d', strtotime($dateSource)) : '';
                    $month = $dateSource ? date('M', strtotime($dateSource)) : '';
                    $slug = $news['slug'] ?? '';
                    $categoriaNombre = $news['categoria_nombre'] ?? 'Noticias';
                    $autor = $news['autor'] ?? 'CAINEC';
                    ?>
                    <div class="col-xl-4 col-md-6">
                      <div class="blog-item wow fadeInUp" data-wow-delay=".1s">
                        <div class="blog-thumb">
                          <a href="blog-details.php?slug=<?= htmlspecialchars($slug) ?>"><img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($news['titulo']) ?>"></a>
                          <div class="blog-date">
                            <span class="date"><?= htmlspecialchars($day) ?></span>
                            <span class="month"><?= htmlspecialchars($month) ?></span>
                          </div>
                        </div>
                        <div class="blog-content">
                          <div class="blog-meta">
                            <span class="categories"><a href="blog-right-sidebar.php?categoria=<?= urlencode($categoriaNombre) ?>"><?= htmlspecialchars($categoriaNombre) ?></a></span>
                            <span>By <a href="blog-details.php?slug=<?= htmlspecialchars($slug) ?>"><?= htmlspecialchars($autor) ?></a></span>
                          </div>
                          <h4 class="title"><a href="blog-details.php?slug=<?= htmlspecialchars($slug) ?>"><?= htmlspecialchars($news['titulo']) ?></a>
                          </h4>
                          <a class="text-btn" href="blog-details.php?slug=<?= htmlspecialchars($slug) ?>">
                            <span class="btn-text"><span>Leer</span></span>
                            <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                          </a>
                        </div>
                      </div>
                    </div>
                    <?php
                }
            }
            ?>
          </div>
          <!-- post pagination -->
          <?php if (isset($totalPages) && $totalPages > 1): ?>
          <div class="tj-pagination d-flex justify-content-center">
            <ul>
              <?php if ($page > 1): ?>
                <li>
                  <a class="prev page-numbers" href="news.php?page=<?= $page - 1 ?>"><i class="tji-arrow-left-long"></i></a>
                </li>
              <?php else: ?>
                <li>
                  <span class="prev page-numbers disabled"><i class="tji-arrow-left-long"></i></span>
                </li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $label = sprintf('%02d', $i); ?>
                <li>
                  <?php if ($i == $page): ?>
                    <span aria-current="page" class="page-numbers current"><?= $label ?></span>
                  <?php else: ?>
                    <a class="page-numbers" href="news.php?page=<?= $i ?>"><?= $label ?></a>
                  <?php endif; ?>
                </li>
              <?php endfor; ?>

              <?php if ($page < $totalPages): ?>
                <li>
                  <a class="next page-numbers" href="news.php?page=<?= $page + 1 ?>"><i class="tji-arrow-right-long"></i></a>
                </li>
              <?php else: ?>
                <li>
                  <span class="next page-numbers disabled"><i class="tji-arrow-right-long"></i></span>
                </li>
              <?php endif; ?>
            </ul>
          </div>
          <?php endif; ?>
        </div>

      </section>
      <!-- end: Blog Section -->

      <?php include 'components/footer.php'; ?>