<?php
$titulo_pagina      = 'Servicios';
$descripcion_pagina = 'Zinguería, canaletas, techados, estructuras metálicas, construcción en seco (Durlock) y herrería a medida.';
require_once 'includes/header.php';
require_once 'includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Lo que hacemos</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Un equipo. Todas las soluciones.</h1>
        </div>
      </section>

      <section class="section section-alt" aria-labelledby="listado-titulo">
        <div class="container">
          <h2 id="listado-titulo" class="visually-hidden">Nuestros servicios</h2>
          <?php require 'includes/servicios-cards.php'; ?>
        </div>
      </section>

      <section id="zingueria" class="section" aria-labelledby="zingueria-titulo">
        <div class="container">
          <div class="row align-items-center g-5">
            <div class="col-lg-6">
              <p class="eyebrow">Zinguería y techados</p>
              <h2 id="zingueria-titulo" class="display-6 fw-bold">Un buen techo.<br />Una preocupación menos.</h2>
              <p class="text-muted-ac mt-4">Protegemos lo que más importa con soluciones que combinan resistencia, precisión y buenas terminaciones.</p>
              <ul class="checklist list-unstyled">
                <li><strong>Chapas para cada proyecto</strong><span>Galvanizadas y prepintadas, según el uso y la terminación.</span></li>
                <li><strong>Plegados a medida</strong><span>Canaletas, bajadas y cenefas adaptadas a tu obra.</span></li>
                <li><strong>Estructuras metálicas</strong><span>Armado de cabriadas y soportes para techos.</span></li>
              </ul>
              <a class="link-acero" href="contacto.php">Consultar por mi techo <span aria-hidden="true">↗</span></a>
            </div>
            <div class="col-lg-6">
              <figure class="photo-tag photo-tag-light mb-0">
                <img src="img/techado.jpg" width="800" height="700" loading="lazy" class="img-fluid rounded-3 w-100"
                     alt="Techo de chapa oscura con canaleta y bajada galvanizada sobre pared de ladrillo" />
                <figcaption>Precisión en cada encuentro</figcaption>
              </figure>
            </div>
          </div>
        </div>
      </section>

      <section id="durlock" class="section section-dark" aria-labelledby="durlock-titulo">
        <div class="container">
          <div class="row align-items-center g-5">
            <div class="col-lg-6">
              <img src="img/durlock.jpg" width="800" height="700" loading="lazy" class="img-fluid rounded-3 w-100"
                   alt="Living con cielorraso de Durlock con iluminación perimetral y grandes ventanales" />
            </div>
            <div class="col-lg-6">
              <p class="eyebrow eyebrow-light">Durlock y herrería</p>
              <h2 id="durlock-titulo" class="display-6 fw-bold text-white">Transformamos espacios.<br />Creamos posibilidades.</h2>
              <p class="text-white-75 mt-4">Soluciones prácticas para renovar tu casa, ampliar un ambiente o sumar seguridad con diseño.</p>
              <h3 class="mini-title">Construcción en seco</h3>
              <p class="text-white-90">Tabiques divisorios · Cielorrasos<br />Revestimientos antihumedad según diagnóstico</p>
              <h3 class="mini-title">Herrería a medida</h3>
              <p class="text-white-90">Portones y rejas · Escaleras<br />Pérgolas y estructuras especiales</p>
              <a class="link-acero link-light-ac" href="contacto.php">Hablemos de tu idea <span aria-hidden="true">↗</span></a>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
