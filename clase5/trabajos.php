<?php
require_once 'includes/funciones.php';

$titulo_pagina      = 'Trabajos realizados';
$descripcion_pagina = 'Buscá entre nuestros trabajos: techos, canaletas, Durlock y herrería a medida.';

$trabajos = [
    ['techos',    'Techos · Vivienda',     'Techo de chapa prepintada',     'galeria-1.jpg', 'Casa de ladrillo con techo de chapa prepintada rodeada de árboles'],
    ['canaletas', 'Zinguería · Detalles',  'Canaletas y bajadas a medida',  'galeria-2.jpg', 'Canaleta y bajada galvanizada instaladas sobre pared de ladrillo'],
    ['durlock',   'Durlock · Interiores',  'Un nuevo espacio para vivir',   'galeria-3.jpg', 'Ambiente interior con cielorraso de Durlock y luz indirecta'],
    ['herreria',  'Herrería · Accesos',    'Portón de líneas simples',      'galeria-4.jpg', 'Portón de herrería de líneas simples en el frente de una casa moderna'],
    ['herreria',  'Herrería · Exteriores', 'Una pérgola para disfrutar',    'galeria-5.jpg', 'Pérgola metálica sobre deck de madera en un jardín'],
    ['durlock',   'Durlock · Cielorrasos', 'Terminaciones que se notan',    'galeria-6.jpg', 'Habitación vacía con cielorraso de Durlock y ventanal con carpintería negra'],
];

$categorias = [
    'todos'     => 'Todas las categorías',
    'techos'    => 'Techos',
    'canaletas' => 'Canaletas',
    'durlock'   => 'Durlock',
    'herreria'  => 'Herrería',
];

// ---------- Formulario por GET: búsqueda y filtro ----------
$q                = obtener_get('q');
$categoria        = obtener_get('categoria');
$errores_busqueda = [];

if ($categoria === '') {
    $categoria = 'todos';
}
if (mb_strlen($q) > 50) {
    $errores_busqueda[] = 'La búsqueda no puede superar los 50 caracteres; se recortó el texto.';
    $q = mb_substr($q, 0, 50);
}
if (!array_key_exists($categoria, $categorias)) {
    $errores_busqueda[] = 'La categoría elegida no es válida; se muestran todas.';
    $categoria = 'todos';
}

$hay_filtro = ($q !== '' || $categoria !== 'todos');

$filtrados = array_filter($trabajos, function (array $trabajo) use ($categoria, $q): bool {
    [$cat, $etiqueta, $titulo] = $trabajo;
    if ($categoria !== 'todos' && $cat !== $categoria) {
        return false;
    }
    return $q === '' || mb_stripos($titulo . ' ' . $etiqueta, $q) !== false;
});
$total = count($filtrados);

require_once 'includes/header.php';
require_once 'includes/nav.php';
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

          <form class="row g-3 align-items-end my-3" method="get" action="trabajos.php" role="search" aria-label="Buscar trabajos">
            <div class="col-md-5">
              <label for="q" class="form-label">Buscar por palabra clave</label>
              <input type="search" class="form-control" id="q" name="q" value="<?= e($q) ?>" maxlength="50" placeholder="Ej. techo, portón, pérgola" />
            </div>
            <div class="col-md-4">
              <label for="categoria" class="form-label">Categoría</label>
              <select class="form-select" id="categoria" name="categoria">
                <?php foreach ($categorias as $clave => $nombre_categoria): ?>
                  <option value="<?= e($clave) ?>" <?= $categoria === $clave ? 'selected' : '' ?>><?= e($nombre_categoria) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-acero flex-grow-1">Buscar</button>
              <a class="btn btn-outline-dark" href="trabajos.php">Limpiar</a>
            </div>
          </form>

          <?php if ($errores_busqueda): ?>
            <div class="alert alert-warning" role="alert">
              <ul class="mb-0">
                <?php foreach ($errores_busqueda as $aviso): ?>
                  <li><?= e($aviso) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ($hay_filtro): ?>
            <p class="text-muted-ac" role="status">
              <?= $total === 1 ? 'Se encontró 1 trabajo' : 'Se encontraron ' . $total . ' trabajos' ?>
              <?php if ($q !== ''): ?>para «<strong><?= e($q) ?></strong>»<?php endif; ?>
              <?php if ($categoria !== 'todos'): ?>en la categoría <strong><?= e($categorias[$categoria]) ?></strong><?php endif; ?>.
            </p>
          <?php endif; ?>

          <?php if ($total === 0): ?>
            <div class="alert alert-info" role="status">
              No encontramos trabajos con esos criterios. <a class="alert-link" href="trabajos.php">Ver todos los trabajos</a>.
            </div>
          <?php else: ?>
            <div class="row g-4" id="galeria">
              <?php foreach ($filtrados as [$cat, $etiqueta, $titulo, $imagen, $alt]): ?>
                <div class="col-md-6 col-lg-4 item" data-cat="<?= e($cat) ?>">
                  <figure class="trabajo">
                    <img src="img/<?= e($imagen) ?>" width="600" height="420" loading="lazy" class="img-fluid rounded-2 w-100" alt="<?= e($alt) ?>" />
                    <figcaption><span><?= e($etiqueta) ?></span><?= e($titulo) ?></figcaption>
                  </figure>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <p class="small text-muted-ac mt-4 mb-0">Galería de referencia: imágenes ilustrativas para reemplazar por fotografías de trabajos propios.</p>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
