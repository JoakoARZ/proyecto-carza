<?php
$titulo_pagina      = 'Nosotros';
$descripcion_pagina = 'Conocé la historia de nuestra familia y nuestro compromiso de trabajo en techos, zinguería, Durlock y herrería.';
require_once 'includes/header.php';
require_once 'includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Nuestra historia</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Un mismo oficio.<br />La misma palabra.</h1>
        </div>
      </section>

      <section class="section" aria-labelledby="familia-titulo">
        <div class="container">
          <div class="row align-items-center g-5">
            <div class="col-lg-6">
              <figure class="photo-tag mb-0">
                <img src="img/historia.jpg" width="800" height="640" loading="lazy" class="img-fluid rounded-3 w-100"
                     alt="Un padre y sus dos hijos revisan una canaleta de chapa galvanizada en el taller" />
                <figcaption>Hecho por nuestras manos</figcaption>
              </figure>
            </div>
            <div class="col-lg-6">
              <h2 id="familia-titulo" class="h3 fw-bold">Somos una familia que trabaja con las manos y responde con hechos.</h2>
              <p class="text-muted-ac mt-4">
                Aprendimos de nuestro papá que un buen trabajo empieza por escuchar y termina cuando todo queda como debe. Hoy, junto a sus hijos, compartimos esa experiencia en cada obra.
              </p>
              <p class="text-muted-ac">
                Cuidamos los detalles, los materiales y tu casa. Porque detrás de cada techo, hay una familia como la nuestra.
              </p>
              <a class="link-acero" href="contacto.php">Trato directo, de nuestra familia a la tuya. <span aria-hidden="true">↗</span></a>
            </div>
          </div>
        </div>
      </section>

      <section class="section section-rust" aria-labelledby="compromiso-titulo">
        <div class="container">
          <div class="row align-items-center g-5">
            <div class="col-lg-6">
              <p class="eyebrow eyebrow-white">Nuestro compromiso</p>
              <h2 id="compromiso-titulo" class="display-6 fw-bold text-white">Tu tranquilidad también es parte del trabajo.</h2>
            </div>
            <div class="col-lg-6">
              <ul class="checklist checklist-white list-unstyled mb-0">
                <li><strong>Trato directo</strong><span>Hablás con quienes hacen la obra.</span></li>
                <li><strong>Claridad desde el inicio</strong><span>Materiales y alcance acordados.</span></li>
                <li><strong>Cuidado en cada detalle</strong><span>Prolijidad durante y después.</span></li>
              </ul>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
