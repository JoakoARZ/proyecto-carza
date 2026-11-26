<?php
$titulo_pagina = 'Inicio';
require_once 'includes/header.php';
require_once 'includes/nav.php';
?>
    <main id="contenido">
      <section class="hero d-flex align-items-center" aria-labelledby="hero-titulo">
        <div class="container py-5">
          <p class="eyebrow eyebrow-light">Oficio familiar. Soluciones que duran.</p>
          <h1 id="hero-titulo" class="display-3 fw-bold text-white">
            Construimos confianza.<br />De generación<br />en generación.
          </h1>
          <p class="lead text-white-75 mt-4">
            Soluciones integrales en techos, zinguería,<br class="d-none d-md-inline" />
            Durlock y herrería. A medida de tu proyecto.
          </p>
          <div class="d-flex flex-wrap gap-3 mt-4">
            <a class="btn btn-acero btn-lg" href="contacto.php">Contanos tu proyecto <span aria-hidden="true">↗</span></a>
            <a class="btn btn-outline-light btn-lg" href="trabajos.php">Ver trabajos realizados <span aria-hidden="true">→</span></a>
          </div>
          <ul class="hero-points list-unstyled d-flex flex-wrap justify-content-between mt-5 mb-0">
            <li>Atención personalizada</li>
            <li>Presupuesto sin cargo</li>
            <li>Fabricación a medida</li>
          </ul>
        </div>
      </section>

      <section class="section section-alt" aria-labelledby="servicios-titulo">
        <div class="container">
          <p class="eyebrow">Lo que hacemos</p>
          <h2 id="servicios-titulo" class="display-6 fw-bold">Un equipo. Todas las soluciones.</h2>
          <p class="text-muted-ac mb-5">De la estructura al último detalle, nos ocupamos de tu proyecto.</p>
          <?php require 'includes/servicios-cards.php'; ?>
        </div>
      </section>

      <section class="section" aria-labelledby="proceso-titulo">
        <div class="container">
          <p class="eyebrow">Paso a paso</p>
          <h2 id="proceso-titulo" class="display-6 fw-bold mb-5">Sin vueltas. De principio a fin.</h2>
          <ol class="row g-4 list-unstyled pasos">
            <li class="col-sm-6 col-lg-3"><span class="num">01</span><h3>Visita y medición</h3><p>Escuchamos tu idea y relevamos las medidas en obra.</p></li>
            <li class="col-sm-6 col-lg-3"><span class="num">02</span><h3>Presupuesto sin cargo</h3><p>Te contamos qué incluye el trabajo y cómo lo vamos a hacer.</p></li>
            <li class="col-sm-6 col-lg-3"><span class="num">03</span><h3>Fabricación a medida</h3><p>Preparamos cada pieza con los materiales acordados.</p></li>
            <li class="col-sm-6 col-lg-3"><span class="num">04</span><h3>Colocación y garantía</h3><p>Instalamos, revisamos los detalles y acompañamos el resultado.</p></li>
          </ol>
        </div>
      </section>

      <section class="section section-rust" aria-labelledby="cta-titulo">
        <div class="container text-center">
          <h2 id="cta-titulo" class="display-6 fw-bold text-white">¿Tenés un proyecto en mente?</h2>
          <p class="text-white-90 mt-3">Visita, medición y presupuesto sin cargo y sin compromiso.</p>
          <a class="btn btn-light btn-lg mt-3" href="contacto.php">Pedí tu presupuesto <span aria-hidden="true">↗</span></a>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
