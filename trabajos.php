<?php
$titulo_pagina      = 'Trabajos realizados';
$descripcion_pagina = 'Galería de trabajos: techos, canaletas, Durlock y herrería a medida.';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$trabajos = [
    ['techos',    'Techos · Vivienda',     'Techo de chapa prepintada',     'galeria-1.jpg', 'Casa de ladrillo con techo de chapa prepintada rodeada de árboles'],
    ['canaletas', 'Zinguería · Detalles',  'Canaletas y bajadas a medida',  'galeria-2.jpg', 'Canaleta y bajada galvanizada instaladas sobre pared de ladrillo'],
    ['durlock',   'Durlock · Interiores',  'Un nuevo espacio para vivir',   'galeria-3.jpg', 'Ambiente interior con cielorraso de Durlock y luz indirecta'],
    ['herreria',  'Herrería · Accesos',    'Portón de líneas simples',      'galeria-4.jpg', 'Portón de herrería de líneas simples en el frente de una casa moderna'],
    ['herreria',  'Herrería · Exteriores', 'Una pérgola para disfrutar',    'galeria-5.jpg', 'Pérgola metálica sobre deck de madera en un jardín'],
    ['durlock',   'Durlock · Cielorrasos', 'Terminaciones que se notan',    'galeria-6.jpg', 'Habitación vacía con cielorraso de Durlock y ventanal con carpintería negra'],
];
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Trabajos realizados</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Nuestro trabajo habla por nosotros.</h1>
        </div>
      </section>

      <section class="section" aria-labelledby="galeria-titulo">
        <div class="container">
          <h2 id="galeria-titulo" class="h4 text-muted-ac fw-normal">Ideas que toman forma. Detalles que hacen la diferencia.</h2>

          <div class="filtros d-flex flex-wrap gap-2 my-4" role="group" aria-label="Filtrar trabajos por categoría">
            <button type="button" class="btn-filtro active" data-filter="todos" aria-pressed="true">Todos</button>
            <button type="button" class="btn-filtro" data-filter="techos" aria-pressed="false">Techos</button>
            <button type="button" class="btn-filtro" data-filter="canaletas" aria-pressed="false">Canaletas</button>
            <button type="button" class="btn-filtro" data-filter="durlock" aria-pressed="false">Durlock</button>
            <button type="button" class="btn-filtro" data-filter="herreria" aria-pressed="false">Herrería</button>
          </div>

          <div class="row g-4" id="galeria">
            <?php foreach ($trabajos as [$cat, $etiqueta, $titulo, $imagen, $alt]): ?>
              <div class="col-md-6 col-lg-4 item" data-cat="<?= e($cat) ?>">
                <figure class="trabajo">
                  <img src="img/<?= e($imagen) ?>" width="600" height="420" loading="lazy" class="img-fluid rounded-2 w-100" alt="<?= e($alt) ?>" />
                  <figcaption><span><?= e($etiqueta) ?></span><?= e($titulo) ?></figcaption>
                </figure>
              </div>
            <?php endforeach; ?>
          </div>
          <p class="small text-muted-ac mt-4 mb-0">Galería de referencia: imágenes ilustrativas para reemplazar por fotografías de trabajos propios.</p>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
