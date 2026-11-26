<?php
// Formulario compartido por crear.php y editar.php.
// Variables esperadas: $datos, $errores, $categorias (id => nombre), $localidades (id => texto), $texto_boton
?>
<form class="form-card" method="post" novalidate>
  <?= csrf_campo() ?>

  <?php if ($errores): ?>
    <div class="alert alert-danger" role="alert">
      <strong>No se pudo guardar.</strong> Revisá estos datos:
      <ul class="mb-0 mt-2">
        <?php foreach ($errores as $mensaje_error): ?>
          <li><?= e($mensaje_error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-12">
      <label for="titulo" class="form-label">Título *</label>
      <input type="text" class="form-control<?= clase_invalida($errores, 'titulo') ?>" id="titulo" name="titulo"
             value="<?= e($datos['titulo']) ?>" maxlength="120" <?= atributos_error($errores, 'titulo') ?> />
      <div class="invalid-feedback" id="error-titulo"><?= e($errores['titulo'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
      <label for="categoria_id" class="form-label">Categoría *</label>
      <select class="form-select<?= clase_invalida($errores, 'categoria_id') ?>" id="categoria_id" name="categoria_id" <?= atributos_error($errores, 'categoria_id') ?>>
        <option value="">Seleccioná una categoría</option>
        <?php foreach ($categorias as $id => $nombre): ?>
          <option value="<?= (int) $id ?>" <?= (string) $id === $datos['categoria_id'] ? 'selected' : '' ?>><?= e($nombre) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback" id="error-categoria_id"><?= e($errores['categoria_id'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
      <label for="localidad_id" class="form-label">Localidad</label>
      <select class="form-select<?= clase_invalida($errores, 'localidad_id') ?>" id="localidad_id" name="localidad_id" <?= atributos_error($errores, 'localidad_id') ?>>
        <option value="">Sin especificar</option>
        <?php foreach ($localidades as $id => $texto): ?>
          <option value="<?= (int) $id ?>" <?= (string) $id === $datos['localidad_id'] ? 'selected' : '' ?>><?= e($texto) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback" id="error-localidad_id"><?= e($errores['localidad_id'] ?? '') ?></div>
    </div>

    <div class="col-12">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea class="form-control<?= clase_invalida($errores, 'descripcion') ?>" id="descripcion" name="descripcion" rows="4"
                maxlength="1100" <?= atributos_error($errores, 'descripcion') ?>><?= e($datos['descripcion']) ?></textarea>
      <div class="invalid-feedback" id="error-descripcion"><?= e($errores['descripcion'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
      <label for="imagen" class="form-label">Imagen (nombre del archivo en <code>img/</code>)</label>
      <input type="text" class="form-control<?= clase_invalida($errores, 'imagen') ?>" id="imagen" name="imagen"
             value="<?= e($datos['imagen']) ?>" placeholder="galeria-1.jpg" maxlength="110" <?= atributos_error($errores, 'imagen') ?> />
      <div class="invalid-feedback" id="error-imagen"><?= e($errores['imagen'] ?? '') ?></div>
    </div>

    <div class="col-md-6">
      <label for="fecha_obra" class="form-label">Fecha de la obra</label>
      <input type="date" class="form-control<?= clase_invalida($errores, 'fecha_obra') ?>" id="fecha_obra" name="fecha_obra"
             value="<?= e($datos['fecha_obra']) ?>" <?= atributos_error($errores, 'fecha_obra') ?> />
      <div class="invalid-feedback" id="error-fecha_obra"><?= e($errores['fecha_obra'] ?? '') ?></div>
    </div>

    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="publicado" name="publicado" value="1" <?= $datos['publicado'] === '1' ? 'checked' : '' ?> />
        <label class="form-check-label" for="publicado">Publicado (visible en la galería del sitio)</label>
      </div>
    </div>

    <div class="col-12 d-flex gap-2">
      <button type="submit" class="btn btn-acero"><?= e($texto_boton) ?></button>
      <a class="btn btn-outline-dark" href="index.php">Cancelar</a>
    </div>
  </div>
</form>
