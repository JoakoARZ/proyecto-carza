<?php
require_once 'includes/funciones.php';
require_once 'config/conexion.php';

$titulo_pagina      = 'Trabajos realizados';
$descripcion_pagina = 'Buscá entre nuestros trabajos: techos, canaletas, Durlock y herrería a medida.';

// Categorías para el filtro (desde la base)
$categorias = $pdo->prepare('SELECT id, nombre FROM categorias ORDER BY nombre');
$categorias->execute();
$categorias = $categorias->fetchAll(PDO::FETCH_KEY_PAIR);   // id => nombre

// ---------- Formulario por GET: búsqueda y filtro ----------
$q                = obtener_get('q');
$categoria_id     = (int) (filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0);
$errores_busqueda = [];

if (mb_strlen($q) > 50) {
    $errores_busqueda[] = 'La búsqueda no puede superar los 50 caracteres; se recortó el texto.';
    $q = mb_substr($q, 0, 50);
}
if ($categoria_id !== 0 && !array_key_exists($categoria_id, $categorias)) {
    $errores_busqueda[] = 'La categoría elegida no es válida; se muestran todas.';
    $categoria_id = 0;
}

// ---------- READ: consulta preparada con filtros opcionales ----------
$sql = 'SELECT t.id, t.titulo, t.descripcion, t.imagen, c.nombre AS categoria
        FROM trabajos t
        INNER JOIN categorias c ON c.id = t.categoria_id
        WHERE t.publicado = 1';
$params = [];

if ($categoria_id !== 0) {
    $sql .= ' AND t.categoria_id = :categoria';
    $params[':categoria'] = $categoria_id;
}
if ($q !== '') {
    // Con consultas preparadas reales, cada marcador se usa una sola vez
    $sql .= ' AND (t.titulo LIKE :q_titulo OR t.descripcion LIKE :q_descripcion)';
    $patron = '%' . addcslashes($q, '%_\\') . '%';
    $params[':q_titulo']      = $patron;
    $params[':q_descripcion'] = $patron;
}
$sql .= ' ORDER BY t.fecha_obra DESC, t.id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$trabajos = $stmt->fetchAll();
$total    = count($trabajos);

$hay_filtro = ($q !== '' || $categoria_id !== 0);

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
              <input type="search" class="form-control" id="q" name="q" value="<?= e($q) ?>" maxlength="50" placeholder="Ej. techo, portón, cielorraso" />
            </div>
            <div class="col-md-4">
              <label for="categoria" class="form-label">Categoría</label>
              <select class="form-select" id="categoria" name="categoria">
                <option value="0">Todas las categorías</option>
                <?php foreach ($categorias as $id_cat => $nombre_cat): ?>
                  <option value="<?= (int) $id_cat ?>" <?= $categoria_id === (int) $id_cat ? 'selected' : '' ?>><?= e($nombre_cat) ?></option>
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
              <?php if ($categoria_id !== 0): ?>en la categoría <strong><?= e($categorias[$categoria_id]) ?></strong><?php endif; ?>.
            </p>
          <?php endif; ?>

          <?php if ($total === 0): ?>
            <div class="alert alert-info" role="status">
              No encontramos trabajos con esos criterios. <a class="alert-link" href="trabajos.php">Ver todos los trabajos</a>.
            </div>
          <?php else: ?>
            <div class="row g-4" id="galeria">
              <?php foreach ($trabajos as $t): ?>
                <div class="col-md-6 col-lg-4 item">
                  <figure class="trabajo">
                    <?php if ($t['imagen']): ?>
                      <img src="img/<?= e($t['imagen']) ?>" width="600" height="420" loading="lazy" class="img-fluid rounded-2 w-100"
                           alt="<?= e($t['titulo'] . ' (' . $t['categoria'] . ')') ?>" />
                    <?php else: ?>
                      <div class="rounded-2 bg-secondary-subtle w-100" style="aspect-ratio: 10 / 7" role="img" aria-label="Sin imagen"></div>
                    <?php endif; ?>
                    <figcaption><span><?= e($t['categoria']) ?></span><?= e($t['titulo']) ?></figcaption>
                  </figure>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
