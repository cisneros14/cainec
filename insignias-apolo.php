<?php

// Características de la Insignia Modesto Apolo
$caracteristicas_insignia = [
    [
        'titulo' => 'Excelencia en Ventas',
        'numero' => '50+',
        'descripcion' => 'Socios reconocidos por superar las metas de ventas anuales y mantener un desempeño sobresaliente.'
    ],
    [
        'titulo' => 'Ética Profesional',
        'numero' => '100%',
        'descripcion' => 'Compromiso inquebrantable con la transparencia, honestidad y las mejores prácticas del sector inmobiliario.'
    ],
    [
        'titulo' => 'Satisfacción del Cliente',
        'numero' => '95%',
        'descripcion' => 'Calificación promedio de satisfacción de clientes atendidos por los galardonados con esta insignia.'
    ]
];

// Conexión a la base de datos y obtención de socios
require_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM socios_apolo ORDER BY orden ASC, created_at DESC");
    $galardonados = $stmt->fetchAll();

    // Decodificar logros
    foreach ($galardonados as &$galardonado) {
        $galardonado['logros'] = json_decode($galardonado['logros'], true) ?? [];
    }
    unset($galardonado); // Romper la referencia para evitar problemas en el siguiente bucle

} catch (PDOException $e) {
    // En caso de error, array vacío para no romper la página
    error_log("Error DB: " . $e->getMessage());
    $galardonados = [];
}

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
                                <h1 class="tj-page-title">Insignias de Reconocimiento</h1>
                                <p class="text-white mt-3 fs-5">Modesto Gerardo Apolo</p>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <a href="reconocimiento-socios.php">Reconocimientos</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Insignias Apolo</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->



            <!-- start: Insignias Explanation Section -->
            <section class="h5-strategy section-gap">
                <div class="container gap-30-30">
                    <div class="row ">
                        <div class="col-12">
                            <div class="sec-heading style-3 h5-strategy-heading text-center flex flex-col items-center">
                                <span class="sub-title wow fadeInUp !text-[2rem]" data-wow-delay=".3s">
                                    Reconocimiento a la Excelencia</span>
                                <h2 class="sec-title text-anim !text-[1.5rem]">Insignia “Modesto Gerardo Apolo Terán”
                                </h2>
                                <img src="assets/images/recursos/insignia.png"
                                    class="w-94 mx-auto animate-bounce !mt-10" data-wow-delay=".4s" alt="">

                                <div class="text-start p-4 md:p-10 mx-auto space-y-6 text-gray-700 text-justify">
                                    <p class="wow fadeInUp  text-justify" data-wow-delay=".4s">
                                        La Cámara Inmobiliaria Ecuatoriana – CAINEC, en el ejercicio de su autonomía
                                        institucional y con el propósito de fortalecer la cultura de reconocimiento,
                                        mérito y liderazgo dentro del gremio inmobiliario nacional, considera necesario
                                        instituir una distinción honorífica que reconozca la trayectoria, el compromiso
                                        y el aporte excepcional de sus miembros.
                                    </p>
                                    <p class="wow fadeInUp  text-justify" data-wow-delay=".5s">
                                        A lo largo de su historia, la CAINEC ha sido fortalecida por mujeres y hombres
                                        que, con vocación de servicio, ética profesional y liderazgo, han contribuido de
                                        manera significativa al desarrollo del sector inmobiliario y al posicionamiento
                                        institucional de la Cámara a nivel nacional.
                                    </p>
                                    <p class="wow fadeInUp  text-justify" data-wow-delay=".6s">
                                        En reconocimiento a esta labor y como homenaje permanente a quienes encarnan los
                                        valores institucionales, se crea la <strong>Insignia “Modesto Gerardo Apolo
                                            Terán”</strong>, como símbolo de excelencia, compromiso gremial y liderazgo;
                                        Distinción otorgada a los miembros de la CAINEC que se hayan destacado de manera
                                        sobresaliente dentro del organismo, cumpliendo méritos de excelencia
                                        institucional, participación activa y sostenida, que los conviertan en
                                        referentes dentro de la industria inmobiliaria y en la estructura nacional de la
                                        Cámara Inmobiliaria Ecuatoriana.
                                    </p>
                                    <p class="wow fadeInUp  text-justify font-medium italic" data-wow-delay=".7s">
                                        La insignia tendrá carácter honorífico y constituirá el máximo reconocimiento
                                        institucional otorgado por la CAINEC a sus miembros.
                                    </p>

                                    <img src="assets/images/recursos/grupo.jpeg" class="w-full !max-w-[600px] !mt-10 mx-auto rounded-2xl" alt="">

                                    <div class="row mt-5">
                                        <!-- Criterios Card -->
                                        <div class="col-lg-6 mb-4 wow fadeInUp" data-wow-delay=".8s">
                                            <div
                                                class="bg-[#f8f9fa] p-4 rounded-xl border border-gray-200 h-100 text-start">
                                                <h3 class="text-lg font-bold text-gray-900 mb-4">Criterios de
                                                    otorgamiento</h3>
                                                <p class="mb-3 text-sm text-gray-600">La Insignia podrá ser otorgada a
                                                    quienes cumplan, al menos, uno o varios de los siguientes criterios:
                                                </p>

                                                <ul class="!space-y-4 text-sm text-gray-700 list-none pl-0">
                                                    <li>- Haber demostrado una trayectoria destacada de servicio
                                                        institucional dentro de la CAINEC.</li>
                                                    <li>- Mantener una participación activa, constante y verificable en
                                                        los órganos, comisiones o actividades del organismo.</li>
                                                    <li>- Haber contribuido de manera significativa al fortalecimiento,
                                                        crecimiento o posicionamiento de la Cámara a nivel local,
                                                        regional, nacional e internacional.</li>
                                                    <li>- Evidenciar cualidades de liderazgo ético, profesional y
                                                        gremial, siendo referente para otros miembros del sector
                                                        inmobiliario.</li>
                                                    <li>- Haber promovido buenas prácticas, innovación,
                                                        profesionalización o prestigio del sector inmobiliario
                                                        ecuatoriano.</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Procedimiento Card -->
                                        <div class="col-lg-6 mb-4 wow fadeInUp" data-wow-delay=".9s">
                                            <div
                                                class="bg-[#f8f9fa] p-4 rounded-xl border border-gray-200 h-100 text-start">
                                                <h3 class="text-lg font-bold text-gray-900 mb-4">Procedimiento de
                                                    postulación</h3>

                                                <div class="!space-y-4 text-sm text-gray-700">
                                                    <div>
                                                        <strong class="block text-gray-900 mb-1">1. Presentación de
                                                            postulación</strong>
                                                        <p class="text-gray-600 mb-1">Podrá ser presentada por:</p>
                                                        <ul class="list-none pl-0 text-gray-600">
                                                            <li>- El Directorio Nacional</li>
                                                            <li>- Los Socios en una Asamblea Nacional</li>
                                                        </ul>
                                                    </div>

                                                    <div>
                                                        <strong class="block text-gray-900 mb-1">2.
                                                            Sustentación</strong>
                                                        <p class="text-gray-600">Las postulaciones deberán estar
                                                            debidamente motivadas y acompañadas de un informe que
                                                            sustente los méritos.</p>
                                                    </div>

                                                    <div>
                                                        <strong class="block text-gray-900 mb-1">3. Evaluación</strong>
                                                        <p class="text-gray-600">El Directorio Nacional evaluará y
                                                            resolverá el otorgamiento mediante resolución motivada.</p>
                                                    </div>

                                                    <div>
                                                        <strong class="block text-gray-900 mb-1">4. Entrega</strong>
                                                        <p class="text-gray-600">En sesiones solemnes, congresos o actos
                                                            oficiales.</p>
                                                    </div>
                                                </div>

                                                <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                                                    La entrega será registrada en los archivos institucionales y podrá
                                                    constar en un Registro de Distinciones Honoríficas.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Insignias Explanation Section -->

            <!-- start: Badges Section -->
            <section class="tj-service-section service-2 section-gap section-gap-x slidebar-stickiy-container"
                style="overflow: visible !important;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4" style="position: relative;">
                            <div style="position: sticky; top: 120px; align-self: flex-start;">
                                <div class="sec-heading style-2">
                                    <span class="sub-title wow fadeInUp !text-white" data-wow-delay=".3s">Socios
                                        Destacados</span>
                                    <h2 class="sec-title text-white text-anim">Galardonados con la Insignia
                                        <span>Modesto Apolo</span>
                                    </h2>
                                </div>
                                <div class="wow fadeInUp" data-wow-delay=".6s">
                                    <a class="tj-primary-btn" href="reconocimiento-socios.php">
                                        <span class="btn-text"><span>Ver Todos los Reconocimientos</span></span>
                                        <span class="btn-icon"><i class="tji-arrow-right-long"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="service-wrapper-2">
                                <?php foreach ($galardonados as $galardonado): ?>
                                    <div class="service-item-wrapper tj-fadein-right-on-scroll">
                                        <div class="service-item style-2">
                                            <div class="title-area">
                                                <div class="service-icon"
                                                    style="background-image: url('<?= htmlspecialchars($galardonado['imagen']) ?>'); background-size: cover; background-position: center;">
                                                </div>
                                                <h4 class="title">
                                                    <?= htmlspecialchars($galardonado['nombre']) ?>
                                                    <span
                                                        class="badge bg-warning text-dark ms-2"><?= htmlspecialchars($galardonado['anio']) ?></span>
                                                </h4>
                                            </div>
                                            <div class="service-content">
                                                <p class="desc"><?= htmlspecialchars($galardonado['cargo']) ?></p>
                                                <ul class="list-items">
                                                    <?php foreach ($galardonado['logros'] as $logro): ?>
                                                        <li><i class="tji-list !text-white"></i><?= htmlspecialchars($logro) ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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
                <div class="bg-shape-3">
                    <img src="assets/images/shape/shape-blur.svg" alt="">
                </div>
            </section>
            <!-- end: Badges Section -->


            <!-- start: Biography Section -->
            <section class="tj-about-section section-gap">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="sec-heading style-2 mb-5">
                                <span class="sub-title wow fadeInUp" data-wow-delay=".3s">Biografía</span>
                                <h2 class="sec-title text-anim">¿Quién es Modesto Gerardo Apolo Terán?</h2>
                                <div class="flex items-center ">

                                    <img src="assets/images/apolo/apolo.jpeg" alt="Perfil Profesional"
                                        class="my-5 md:my-10 md:!mr-14 w-full max-w-[700px] rounded-lg shadow">
                                    <div class="desc mt-4 wow fadeInUp" data-wow-delay=".4s">
                                        <p class="fs-5 text-dark">
                                            Modesto Gerardo Apolo Terán es un Doctor en jurisprudencia, casado,
                                            pragmático, humanista, generador de empleo. Solidario, cree en la familia
                                            tradicional, en la independencia de los hijos y un Dios todopoderoso.
                                        </p>
                                        <p>
                                            Ama la navegación a vela, las excursiones al aire libre y conocer nuevas
                                            culturas. Es un jurista ecuatoriano destacado, con una amplia trayectoria en
                                            el ámbito del derecho laboral y el análisis político.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Perfil Profesional -->
                        <div class="col-lg-6 mb-4">
                            <div class="service-item h-100 wow fadeInUp flex flex-col items-start" data-wow-delay=".3s">

                                <h4 class="title !text-black">Perfil Profesional</h4>
                                <div class="service-content">
                                    <div class="mb-3">
                                        <h5 class="fs-6 text-primary">Formación y especialización</h5>
                                        <p>Es Doctor en Jurisprudencia y se especializa en Derecho Laboral. Su expertise
                                            lo ha posicionado como una voz autorizada en temas de reformas legales y
                                            políticas públicas en Ecuador.</p>
                                    </div>
                                    <div class="mb-3">
                                        <h5 class="fs-6 text-primary">Carrera destacada</h5>
                                        <p>Ha participado activamente en debates sobre modernización del Código Laboral
                                            ecuatoriano, enfatizando la necesidad de adaptarlo a nuevas modalidades
                                            laborales sin precarizar los derechos de los trabajadores.</p>
                                    </div>
                                    <div>
                                        <h5 class="fs-6 text-primary">Ponencias y apariciones públicas</h5>
                                        <p>En mayo de 2020, fue ponente en un foro de la Alianza Latinoamericana por la
                                            Democracia y el Desarrollo, disertando sobre "El mito de la flexibilización
                                            como precarización". Invitado frecuente en programas como Telerama Noticias.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contribuciones y Roles -->
                        <div class="col-lg-6 mb-4">
                            <div class="service-item h-100 wow fadeInUp" data-wow-delay=".5s">

                                <h4 class="title">Contribuciones y Trayectoria</h4>
                                <div class="service-content">
                                    <div class="mb-3">
                                        <h5 class="fs-6 text-primary">Contribuciones Recientes</h5>
                                        <p>En agosto de 2025, publicó la columna "No más escasez de medicinas" en Diario
                                            Expreso, analizando estrategias para el sector salud. Esto refleja su rol
                                            como analista político más allá del derecho laboral.</p>
                                    </div>
                                    <div class="mb-3">
                                        <h5 class="fs-6 text-primary">Roles Destacados</h5>
                                        <ul class="list-items">
                                            <li><i class="tji-check-circle text-primary"></i> Articulista permanente de
                                                Diario Expreso de Guayaquil.</li>
                                            <li><i class="tji-check-circle text-primary"></i> Fundador de la CAMARA
                                                INMOBILIARIA ECUATORIANA CAINEC.</li>
                                            <li><i class="tji-check-circle text-primary"></i> Mediador del Centro de
                                                Mediación de la UEES.</li>
                                            <li><i class="tji-check-circle text-primary"></i> Ex profesor Universitario
                                                (19 años) en Universidad Católica de Guayaquil y UEES.</li>
                                            <li><i class="tji-check-circle text-primary"></i> Sindico de ACBIR GUAYAS.
                                            </li>
                                            <li><i class="tji-check-circle text-primary"></i> Sindico de "WORLD BASC
                                                Organization" (Capitulo Guayaquil).</li>
                                            <li><i class="tji-check-circle text-primary"></i> Sindico del Consorcio
                                                Guayaquil.</li>
                                            <li><i class="tji-check-circle text-primary"></i> Gerente Propietario de
                                                CONVERGENTE TV.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Biography Section -->

        </main>

        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>