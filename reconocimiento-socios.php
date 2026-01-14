<?php
// Datos de Reconocimientos (Estrellas CAINEC)
$estrellas = [
    [
        'id' => 1,
        'nombre' => 'Inmobiliaria El Bosque',
        'categoria' => 'Trayectoria Empresarial',
        'imagen' => 'assets/images/team/team-1.webp', // Placeholder
        'descripcion' => 'Por sus 25 años de servicio ininterrumpido y excelencia en el desarrollo urbano.',
        'anio' => '2024'
    ],
    [
        'id' => 2,
        'nombre' => 'Arq. Roberto Sanchez',
        'categoria' => 'Innovación Sostenible',
        'imagen' => 'assets/images/team/team-2.webp', // Placeholder
        'descripcion' => 'Premio a la mejor iniciativa de construcción ecológica en el proyecto "Verde Horizonte".',
        'anio' => '2024'
    ],
    [
        'id' => 3,
        'nombre' => 'Constructora Millenium',
        'categoria' => 'Responsabilidad Social',
        'imagen' => 'assets/images/team/team-3.webp', // Placeholder
        'descripcion' => 'Reconocimiento por su programa de vivienda accesible para comunidades vulnerables.',
        'anio' => '2023'
    ],
    [
        'id' => 4,
        'nombre' => 'Dra. Carmen Velasco',
        'categoria' => 'Liderazgo Gremial',
        'imagen' => 'assets/images/team/team-4.webp', // Placeholder
        'descripcion' => 'Por su destacada gestión en la unificación del sector inmobiliario en la región sur.',
        'anio' => '2023'
    ],
    [
        'id' => 5,
        'nombre' => 'Grupo Inversor Andes',
        'categoria' => 'Mayor Crecimiento',
        'imagen' => 'assets/images/team/team-5.webp', // Placeholder
        'descripcion' => 'Empresa con mayor expansión y generación de empleo en el último año fiscal.',
        'anio' => '2024'
    ],
    [
        'id' => 6,
        'nombre' => 'Ing. David Torres',
        'categoria' => 'Joven Promesa',
        'imagen' => 'assets/images/team/team-6.webp', // Placeholder
        'descripcion' => 'Reconocimiento al emprendedor joven más destacado del sector inmobiliario.',
        'anio' => '2024'
    ]
];

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
                                <h1 class="tj-page-title">Reconocimiento a Socios</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Reconocimientos</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Awards Section -->
            <section class="tj-team-section section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sec-heading text-center">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".1s"><i
                                        class="tji-award"></i>Excelencia CAINEC</span>
                                <h2 class="sec-title title-anim">Estrellas <span>CAINEC</span></h2>
                                <p class="desc wow fadeInUp" data-wow-delay=".3s">
                                    Celebramos a quienes con su esfuerzo, ética y resultados elevan el estándar de
                                    nuestra industria.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($estrellas as $index => $estrella): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="team-item wow fadeInUp" data-wow-delay=".<?= ($index % 3 + 1) ?>s">
                                    <div class="team-img">
                                        <div class="team-img-inner">
                                            <img src="<?= htmlspecialchars($estrella['imagen']) ?>"
                                                alt="<?= htmlspecialchars($estrella['nombre']) ?>">
                                        </div>
                                        <div class="social-links">
                                            <span class="badge bg-warning text-dark p-2 rounded-pill fw-bold">
                                                <i class="tji-star"></i> <?= htmlspecialchars($estrella['anio']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="team-content text-center">
                                        <h4 class="title"><?= htmlspecialchars($estrella['nombre']) ?></h4>
                                        <span
                                            class="designation text-primary mb-2 d-block"><?= htmlspecialchars($estrella['categoria']) ?></span>
                                        <p class="desc text-sm text-gray-600 px-3">
                                            <?= htmlspecialchars($estrella['descripcion']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <!-- end: Awards Section -->



        </main>

        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>