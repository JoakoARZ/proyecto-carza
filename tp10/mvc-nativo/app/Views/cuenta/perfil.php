<section class="page-banner"><div class="container">
  <p class="eyebrow eyebrow-light">Mi cuenta</p>
  <h1 class="display-5 fw-bold text-white">Hola, <?= e($usuario['nombre']) ?></h1>
</div></section>

<section class="section">
  <div class="container">
    <h2 class="h4 fw-bold">Mis datos</h2>
    <dl class="row mt-3">
      <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9"><?= e($usuario['nombre'] . ' ' . $usuario['apellido']) ?></dd>
      <dt class="col-sm-3">Correo</dt><dd class="col-sm-9"><?= e($usuario['email']) ?></dd>
      <dt class="col-sm-3">Rol</dt><dd class="col-sm-9"><span class="badge text-bg-secondary"><?= e($usuario['rol']) ?></span></dd>
      <dt class="col-sm-3">Miembro desde</dt><dd class="col-sm-9"><?= e($usuario['created_at']) ?></dd>
    </dl>
  </div>
</section>
