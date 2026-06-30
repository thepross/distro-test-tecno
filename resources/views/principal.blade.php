<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Restaurante "La Ñ"</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Restaurantly
  * Template URL: https://bootstrapmade.com/restaurantly-restaurant-template/
  * Updated: Jun 14 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header fixed-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-instagram d-flex align-items-center"><a href="https://www.instagram.com/lanrestobar">lanrestobar</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>71044826</span></i>
        </div>
        <div class="d-flex d-md-flex align-items-center">
          @if (Route::has('login'))
              <nav class="-mx-3 flex flex-1 justify-end">
                  @auth
                      <a
                          href="{{ url('/dashboard') }}"
                          class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                      >
                          Dashboard
                      </a>
                  @else
                      <a
                          href="{{ route('login') }}"
                          class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                      >
                          Log in
                      </a>
                  @endauth
              </nav>
          @endif
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-cente">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Restaurante Español La "Ñ"</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="active">Home<br></a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#specials">Specials</a></li>
            <li><a href="#chefs">Chefs</a></li>
            <li><a href="#gallery">Gallery</a></li>
            
            <li><a href="#contact">Contact</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        

      </div>

    </div>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

      <div class="container">
        <div class="row">
          <div class="col-lg-8 d-flex flex-column align-items-center align-items-lg-start">
            <h2 data-aos="fade-up" data-aos-delay="100">Bienvenido a <span>Restaurante La "Ñ"</span></h2>
            <p data-aos="fade-up" data-aos-delay="200">¡Ofreciendo excelente comida durante más de 18 años!</p>
            <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
              <a href="#menu" class="cta-btn">Nuestro Menu</a>
              
            </div>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-center mt-5 mt-lg-0">
            <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox play-btn"></a>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 order-1 order-lg-2">
            <img src="assets/img/about.jpg" class="img-fluid about-img" alt="">
          </div>
          <div class="col-lg-6 order-2 order-lg-1 content">
            <h3>Sabores Auténticos de España</h3>
            <p class="fst-italic">
              Sumérgete en la rica tradición culinaria española, donde cada plato es una obra maestra de sabor y pasión.
            </p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span>Degusta nuestras tapas, una explosión de sabores en cada bocado.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Deléitate con nuestras paellas, un clásico que te transportará a las costas mediterráneas.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Descubre la magia de nuestros platos de mariscos frescos, traídos directamente del mar a tu mesa.</span></li>
            </ul>
            <p>
              Acompáñanos en un viaje gastronómico por España, donde cada ingrediente cuenta una historia y cada plato es una experiencia inolvidable. ¡Reserva tu mesa ahora y déjate seducir por la auténtica cocina española!
            </p>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Why Us Section -->
    <section id="why-us" class="why-us section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
    <h2>¿POR QUÉ ELEGIRNOS?</h2>
    <p>Las razones para elegir nuestro restaurante</p>
</div><!-- End Section Title -->

<div class="container">

    <div class="row gy-4">

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-item">
                <span>01</span>
                <h4><a href="" class="stretched-link">Ingredientes Frescos</a></h4>
                <p>Utilizamos solo los ingredientes más frescos y de la mejor calidad para ofrecerte platos excepcionales.</p>
            </div>
        </div><!-- Card Item -->

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-item">
                <span>02</span>
                <h4><a href="" class="stretched-link">Tradición y Sabor</a></h4>
                <p>Cada plato está elaborado siguiendo las recetas tradicionales españolas, garantizando un sabor auténtico.</p>
            </div>
        </div><!-- Card Item -->

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card-item">
                <span>03</span>
                <h4><a href="" class="stretched-link">Atención Personalizada</a></h4>
                <p>Nuestro equipo se dedica a brindarte una experiencia memorable, con un servicio atento y cordial.</p>
            </div>
        </div><!-- Card Item -->

    </div>

</div>

    </section><!-- /Why Us Section -->

    <!-- Menu Section -->
    <section id="menu" class="menu section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
    <h2>MENÚ</h2>
    <p>Descubre nuestro delicioso menú</p>
</div><!-- End Section Title -->

<div class="container isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

    <div class="row" data-aos="fade-up" data-aos-delay="100">
        <div class="col-lg-12 d-flex justify-content-center">
            <ul class="menu-filters isotope-filters">
                <li data-filter="*" class="filter-active">Todo</li>
                <li data-filter=".filter-starters">Entrantes</li>
                <li data-filter=".filter-salads">Ensaladas</li>
                <li data-filter=".filter-specialty">Especialidades</li>
            </ul>
        </div>
    </div><!-- Menu Filters -->

    <div class="row isotope-container" data-aos="fade-up" data-aos-delay="200">

        <div class="col-lg-6 menu-item isotope-item filter-starters">
            <img src="assets/img/menu/lobster-bisque.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Crema de Langosta</a><span>$5.95</span>
            </div>
            <div class="menu-ingredients">
                Suave crema de langosta con un toque especial.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-specialty">
            <img src="assets/img/menu/bread-barrel.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Pan Artesanal</a><span>$6.95</span>
            </div>
            <div class="menu-ingredients">
                Pan fresco hecho en casa, ideal para acompañar.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-starters">
            <img src="assets/img/menu/cake.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Tortita de Cangrejo</a><span>$7.95</span>
            </div>
            <div class="menu-ingredients">
                Delicada tortita de cangrejo servida en pan tostado con lechuga y salsa tártara.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-salads">
            <img src="assets/img/menu/caesar.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Ensalada César</a><span>$8.95</span>
            </div>
            <div class="menu-ingredients">
                Ensalada clásica con lechuga romana, crutones y aderezo César.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-specialty">
            <img src="assets/img/menu/tuscan-grilled.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Pollo a la Toscana</a><span>$9.95</span>
            </div>
            <div class="menu-ingredients">
                Pollo a la parrilla con provolone, corazones de alcachofa y pesto asado.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-starters">
            <img src="assets/img/menu/mozzarella.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Palitos de Mozzarella</a><span>$4.95</span>
            </div>
            <div class="menu-ingredients">
                Crujientes palitos de mozzarella, perfectos para compartir.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-salads">
            <img src="assets/img/menu/greek-salad.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Ensalada Griega</a><span>$9.95</span>
            </div>
            <div class="menu-ingredients">
                Espinacas frescas, lechuga crujiente, tomates y aceitunas griegas.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-salads">
            <img src="assets/img/menu/spinach-salad.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Ensalada de Espinacas</a><span>$9.95</span>
            </div>
            <div class="menu-ingredients">
                Espinacas frescas con champiñones, huevo duro y vinagreta de tocino caliente.
            </div>
        </div><!-- Menu Item -->

        <div class="col-lg-6 menu-item isotope-item filter-specialty">
            <img src="assets/img/menu/lobster-roll.jpg" class="menu-img" alt="">
            <div class="menu-content">
                <a href="#">Rollito de Langosta</a><span>$12.95</span>
            </div>
            <div class="menu-ingredients">
                Jugosa carne de langosta con mayonesa y lechuga en un pan tostado.
            </div>
        </div><!-- Menu Item -->

    </div><!-- Menu Container -->

</div>


    </section><!-- /Menu Section -->

    <!-- Specials Section -->
    <section id="specials" class="specials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
    <h2>OFERTAS ESPECIALES</h2>
    <p>Descubre nuestras ofertas especiales</p>
</div><!-- End Section Title -->

<div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row">
        <div class="col-lg-3">
            <ul class="nav nav-tabs flex-column">
                <li class="nav-item">
                    <a class="nav-link active show" data-bs-toggle="tab" href="#specials-tab-1">Plato del Día</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-2">Recomendaciones del Chef</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-3">Sabores de Temporada</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-4">Postres Especiales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-5">Bebidas Destacadas</a>
                </li>
            </ul>
        </div>
        <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="tab-content">
                <div class="tab-pane active show" id="specials-tab-1">
                    <div class="row">
                        <div class="col-lg-8 details order-2 order-lg-1">
                            <h3>Plato del Día</h3>
                            <p class="fst-italic">Una deliciosa selección de la cocina tradicional.</p>
                            <p>Disfruta de una experiencia única con ingredientes frescos y locales, preparados con amor y dedicación.</p>
                        </div>
                        <div class="col-lg-4 text-center order-1 order-lg-2">
                            <img src="assets/img/specials-1.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="specials-tab-2">
                    <div class="row">
                        <div class="col-lg-8 details order-2 order-lg-1">
                            <h3>Recomendaciones del Chef</h3>
                            <p class="fst-italic">Platos elaborados con ingredientes seleccionados.</p>
                            <p>Sabores que deleitarán tu paladar, siempre buscando la perfección en cada bocado.</p>
                        </div>
                        <div class="col-lg-4 text-center order-1 order-lg-2">
                            <img src="assets/img/specials-2.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="specials-tab-3">
                    <div class="row">
                        <div class="col-lg-8 details order-2 order-lg-1">
                            <h3>Sabores de Temporada</h3>
                            <p class="fst-italic">Platos que celebran la frescura de cada estación.</p>
                            <p>Con ingredientes de temporada, garantizamos calidad y sabor en cada plato.</p>
                        </div>
                        <div class="col-lg-4 text-center order-1 order-lg-2">
                            <img src="assets/img/specials-3.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="specials-tab-4">
                    <div class="row">
                        <div class="col-lg-8 details order-2 order-lg-1">
                            <h3>Postres Especiales</h3>
                            <p class="fst-italic">Delicias para cerrar con broche de oro.</p>
                            <p>Nuestros postres son elaborados con recetas tradicionales que te harán querer repetir.</p>
                        </div>
                        <div class="col-lg-4 text-center order-1 order-lg-2">
                            <img src="assets/img/specials-4.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="specials-tab-5">
                    <div class="row">
                        <div class="col-lg-8 details order-2 order-lg-1">
                            <h3>Bebidas Destacadas</h3>
                            <p class="fst-italic">Una selección de bebidas que complementan perfectamente tus comidas.</p>
                            <p>Desde vinos locales hasta cócteles creativos, nuestra carta de bebidas te sorprenderá.</p>
                        </div>
                        <div class="col-lg-4 text-center order-1 order-lg-2">
                            <img src="assets/img/specials-5.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


    </section><!-- /Specials Section -->

    <!-- Events Section -->
    <section id="events" class="events section">

      <img class="slider-bg" src="assets/img/events-bg.jpg" alt="" data-aos="fade-in">

      <div class="container">

        <div class="swiper init-swiper" data-aos="fade-up" data-aos-delay="100">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/events-slider-1.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                    <h3>Fiestas de Cumpleaños</h3>
                    <div class="price">
                        <p><span>$189</span></p>
                    </div>
                    <p class="fst-italic">
                        Celebra tu cumpleaños con nosotros y disfruta de un día especial lleno de sorpresas.
                    </p>
                    <ul>
                        <li><i class="bi bi-check2-circle"></i> <span>Menú personalizado para todos los gustos.</span></li>
                        <li><i class="bi bi-check2-circle"></i> <span>Decoraciones temáticas según tus preferencias.</span></li>
                        <li><i class="bi bi-check2-circle"></i> <span>Asistencia en la planificación de la celebración.</span></li>
                    </ul>
                    <p>
                        Nos encargamos de cada detalle para que tu fiesta de cumpleaños sea inolvidable. ¡Déjanos crear momentos especiales para ti!
                    </p>
                </div>
              </div>
            </div><!-- End Slider item -->

            <div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/events-slider-2.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Fiestas Privadas</h3>
                  <div class="price">
                      <p><span>$290</span></p>
                  </div>
                  <p class="fst-italic">
                      Organiza tu evento con nosotros y disfruta de un servicio excepcional y un ambiente acogedor.
                  </p>
                  <ul>
                      <li><i class="bi bi-check2-circle"></i> <span>Servicio personalizado para cada evento.</span></li>
                      <li><i class="bi bi-check2-circle"></i> <span>Opciones de menú adaptadas a tus necesidades.</span></li>
                      <li><i class="bi bi-check2-circle"></i> <span>Espacio privado para grupos grandes.</span></li>
                  </ul>
                  <p>
                      Contamos con una amplia experiencia en la organización de eventos. Nuestro equipo se encargará de cada detalle para que tu celebración sea inolvidable.
                  </p>
              </div>

              </div>
            </div><!-- End Slider item -->

            <div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/events-slider-3.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Fiestas Personalizadas</h3>
                  <div class="price">
                      <p><span>$99</span></p>
                  </div>
                  <p class="fst-italic">
                      Organiza una celebración única adaptada a tus preferencias y necesidades.
                  </p>
                  <ul>
                      <li><i class="bi bi-check2-circle"></i> <span>Opciones de menú personalizadas para cada ocasión.</span></li>
                      <li><i class="bi bi-check2-circle"></i> <span>Decoración y ambientación a tu gusto.</span></li>
                      <li><i class="bi bi-check2-circle"></i> <span>Asesoramiento para planificar tu evento.</span></li>
                  </ul>
                  <p>
                      Nos encargamos de todos los detalles para que tu fiesta sea inolvidable. ¡Déjanos ayudarte a crear recuerdos especiales!
                  </p>
              </div>

              </div>
            </div><!-- End Slider item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Events Section -->

    

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <p>What they're saying about us</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper" data-speed="600" data-delay="5000" data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item" "="">
            <p>
              <i class=" bi bi-quote quote-icon-left"></i>
                <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Saul Goodman</h3>
                <h4>Ceo &amp; Founder</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                <h3>Sara Wilsson</h3>
                <h4>Designer</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                <h3>Jena Karlis</h3>
                <h4>Store Owner</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                <h3>Matt Brandon</h3>
                <h4>Freelancer</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                <h3>John Larson</h3>
                <h4>Entrepreneur</h4>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Some photos from Our Restaurant</p>
      </div><!-- End Section Title -->

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-3.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-3.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-4.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-4.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-5.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-5.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-6.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-6.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-7.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-7.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-8.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-8.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

        </div>

      </div>

    </section><!-- /Gallery Section -->

    <!-- Chefs Section -->
    <section id="chefs" class="chefs section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Team</h2>
        <p>Necessitatibus eius consequatur</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <img src="assets/img/chefs/chefs-1.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Walter White</h4>
                  <span>Master Chef</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <img src="assets/img/chefs/chefs-2.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Sarah Jhonson</h4>
                  <span>Patissier</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <img src="assets/img/chefs/chefs-3.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>William Anderson</h4>
                  <span>Cook</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Chefs Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contacto</h2>
        <p>Contactanos</p>
      </div><!-- End Section Title -->

      <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
          <iframe style="border:0; width: 100%; height: 400px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3799.498590430507!2d-63.19942619651557!3d-17.768249552482462!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1e7f9b986d12d%3A0xbe415f27ab01f1a9!2sCalle%20340%2C%20Santa%20Cruz%20de%20la%20Sierra!5e0!3m2!1ses!2sbo!4v1721156642990!5m2!1ses!2sbo" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div><!-- End Google Maps -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-12">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Ubicación</h3>
                <p>Calle 340, Santa Cruz de la Sierra</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Horario de atención</h3>
                <p>Lunes-Sábado:<br>11:00 AM - 11:00 PM</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Llámanos</h3>
                <p>+591 71044826</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Envíanos un correo</h3>
                <p>restaurantlaene@gmail.com</p>
              </div>
            </div><!-- End Info Item -->

          </div>

          

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Restaurante La Ñ</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Calle 340, Santa Cruz de la Sierra</p>
            <p>Santa Cruz, SC 340</p>
            <p class="mt-3"><strong>Telefono:</strong> <span>+591 71044826</span></p>
            <p><strong>Email:</strong> <span>restaurantlaene@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href="https://www.facebook.com/lanrestobar"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/lanrestobar"><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Netcrow</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">CevaSoft</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
