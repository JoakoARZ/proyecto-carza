<section class="page-banner">
  <div class="container">
    <p class="eyebrow eyebrow-light">Catálogo</p>
    <h1 class="display-5 fw-bold text-white">Trabajos realizados</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <form class="row g-3 align-items-end mb-3" method="get" action="<?= e(url('/trabajos')) ?>" role="search" aria-label="Buscar trabajos">
      <div class="col-md-5">
        <label for="q" class="form-label">Buscar por palabra clave</label>
        <input type="search" class="form-control" id="q" name="q" value="<?= e($q) ?>" maxlength="50" placeholder="Ej. techo, portón" />
      </div>
      <div class="col-md-4">
        <label for="categoria" class="form-label">Categoría</label>
        <select class="form-select" id="categoria" name="categoria">
          <option value="0">Todas las categorías</option>
          <?php foreach ($categorias as $id => $nombre): ?>
            <option value="<?= (int) $id ?>" <?= $categoriaId === (int) $id ? 'selected' : '' ?>><?= e($nombre) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-acero flex-grow-1">Buscar</button>
        <a class="btn btn-outline-dark" href="<?= e(url('/trabajos')) ?>">Limpiar</a>
      </div>
    </form>

    <?php if ($avisos): ?>
      <div class="alert alert-warning" role="alert"><ul class="mb-0"><?php foreach ($avisos as $a): ?><li><?= e($a) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <p class="text-muted-ac" role="status"><?= count($trabajos) === 1 ? '1 trabajo encontrado' : count($trabajos) . ' trabajos encontrados' ?>.</p>

    <?php if (!$trabajos): ?>
      <div class="alert alert-info">No encontramos trabajos con esos criterios.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($trabajos as $t): ?>
          <div class="col-md-6 col-lg-4">
            <figure class="trabajo">
              <?php if ($t['imagen']): ?>
                <img src="<?= e(asset('img/' . $t['imagen'])) ?>" width="600" height="420" loading="lazy" class="img-fluid rounded-2 w-100"
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
