<?php include 'components/header.php'; ?>

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
                                <h1 class="tj-page-title">Informacion del Socio</h1>
                                <div class="tj-page-link">
                                    <span><i class="tji-home text-white"></i></span>
                                    <span>
                                        <a href="index.php">Inicio</a>
                                    </span>
                                    <span><i class="tji-arrow-right"></i></span>
                                    <span>
                                        <span>Informacion del Socio</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Breadcrumb Section -->

            <!-- start: Team Details Section -->
            <section class="team-details slidebar-stickiy-container">

                <div class="container">
                    <div class="row justify-content-center">
                        <!--  left -->
                        <div class="col-12 col-md-8 col-lg-5">
                            <div class="team-details__img sticky top-[30px] wow fadeInUp" data-wow-delay=".1s">
                                <!-- <img src="./assets/images/team/team-1-big.webp" alt=""> -->
                                <img src="https://i.pinimg.com/1200x/b5/2a/21/b52a21ead1af53a2ad6ec696460866c8.jpg" alt="">
                            </div>
                        </div>
                        <!-- right -->
                        <div class="col-12 col-lg-7 ">
                            <div class="team-details__content">
                                <h2 class="team-details__name title-anim">Hello, I am Eade Marren</h2>
                                <span class="team-details__desig wow fadeInUp" data-wow-delay=".1s">Chief
                                    Executive</span>
                                <p class="wow fadeInUp" data-wow-delay=".3s">Our mission is to empowers businesses
                                    sizes thrive
                                    businesses
                                    ev changing marketplace We are
                                    committed to the delivering exceptional value through strategic inset innovative
                                    approaches. Our
                                    consulting of our missing empower.</p>
                                <div class="team-details__contact-info wow fadeInUp" data-wow-delay=".5s">
                                    <ul>
                                        <li>
                                            <span>Email address</span>
                                            <a href="mailto:eade.marren@bexon.com">eade.marren@bexon.com</a>
                                        </li>
                                        <li>
                                            <span>Phone number</span>
                                            <a href="tel:10095447818">+1 (009) 544-7818</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="social-links wow fadeInUp" data-wow-delay=".5s">
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
                                <div class="team-details__experience">
                                    <h4 class="team-details__subtitle wow fadeInUp" data-wow-delay=".3s">Work
                                        experience</h4>
                                    <p class="wow fadeInUp" data-wow-delay=".3s">Our mission is to empowers
                                        businesses size to thrivie
                                        in
                                        ses ever changing marketplace We are
                                        committed to the delivering exceptionals the value thro strategic ins
                                        innovative approaches. Our
                                        consulting of our missing empowers businesses of all sizes Committed to the
                                        delivering exceptional
                                        in
                                        the values</p>
                                    <p class="wow fadeInUp" data-wow-delay=".3s">Our mission is to empowers
                                        businesses size to thrivie
                                        in
                                        ses ever changing marketplace We are
                                        committed to the delivering exceptionals the value thro strategic ins
                                        innovative approaches. Our
                                        consulting of our missing empowers</p>
                                    <div class="team-details__experience__list wow fadeInUp" data-wow-delay=".3s">
                                        <ul>
                                            <li><i class="tji-check"></i>
                                                <p>We believe that the human essential start any successful project.
                                                </p>
                                            </li>
                                            <li><i class="tji-check"></i>
                                                <p>We believe that the human essential start any successful project.
                                                </p>
                                            </li>
                                            <li><i class="tji-check"></i>
                                                <p>We believe that the human essential start any successful project.
                                                </p>
                                            </li>
                                            <li><i class="tji-check"></i>
                                                <p>We believe that the human essential start any successful project.
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-details__skills">
                                    <h4 class="team-details__subtitle wow fadeInUp" data-wow-delay=".3s">
                                        Professional skills</h4>
                                    <p class="wow fadeInUp" data-wow-delay=".3s">Our mission is to empowers
                                        businesses size to thrivie
                                        in
                                        ses ever changing marketplace We are
                                        committed to the delivering exceptionals the value thro strategic ins
                                        innovative approaches. Our
                                        consulting of our missing empowers.</p>
                                    <ul class="tj-progress-list wow fadeInUp" data-wow-delay=".3s">
                                        <li>
                                            <h6 class="tj-progress-title">Business Consultants</h6>
                                            <div class="tj-progress">
                                                <span class="tj-progress-percent">82%</span>
                                                <div class="tj-progress-bar" data-percent="82">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6 class="tj-progress-title">Client Communication</h6>
                                            <div class="tj-progress">
                                                <span class="tj-progress-percent">90%</span>
                                                <div class="tj-progress-bar" data-percent="90">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Team Details Section -->
            <!-- start: Footer Section -->
            <?php include 'components/footer.php'; ?>