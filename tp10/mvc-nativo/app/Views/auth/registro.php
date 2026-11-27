<?php
// Pequeña ayuda local para marcar los campos con error
$inv = fn (string $campo): string => isset($errores[$campo]) ? ' is-invalid' : '';
?>
<section class="page-banner"><div class="container">
  <p class="eyebrow eyebrow-light">Tu cuenta</p><h1 class="display-5 fw-bold text-white">Crear cuenta</h1>
</div></section>

<section class="section section-alt">
  <div class="container">
    <div class="row justify-content-center"><div class="col-md-8 col-lg-6">
      <form class="form-card" method="post" action="<?= e(url('/registro')) ?>" novalidate>
        <?= csrf_campo() ?>
        <?php if ($errores): ?>
          <div class="alert alert-danger" role="alert"><strong>No pudimos crear tu cuenta.</strong> Revisá estos datos:
            <ul class="mb-0 mt-2"><?php foreach ($errores as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>
        <div class="row g-3">
          <div class="col-sm-6">
            <label for="nombre" class="form-label">Nombre *</label>
            <input type="text" class="form-control<?= $inv('nombre') ?>" id="nombre" name="nombre" value="<?= e($valores['nombre']) ?>" maxlength="60" autocomplete="given-name" />
            <div class="invalid-feedback"><?= e($errores['nombre'] ?? '') ?></div>
          </div>
          <div class="col-sm-6">
            <label for="apellido" class="form-label">Apellido *</label>
            <input type="text" class="form-control<?= $inv('apellido') ?>" id="apellido" name="apellido" value="<?= e($valores['apellido']) ?>" maxlength="60" autocomplete="family-name" />
            <div class="invalid-feedback"><?= e($errores['apellido'] ?? '') ?></div>
          </div>
          <div class="col-12">
            <label for="email" class="form-label">Correo electrónico *</label>
            <input type="email" class="form-control<?= $inv('email') ?>" id="email" name="email" value="<?= e($valores['email']) ?>" maxlength="120" autocomplete="email" />
            <div class="invalid-feedback"><?= e($errores['email'] ?? '') ?></div>
          </div>
          <div class="col-sm-6">
            <label for="password" class="form-label">Contraseña *</label>
            <input type="password" class="form-control<?= $inv('password') ?>" id="password" name="password" maxlength="72" autocomplete="new-password" />
            <div class="invalid-feedback"><?= e($errores['password'] ?? '') ?></div>
            <div class="form-text">Mínimo 8 caracteres, con letras y números.</div>
          </div>
          <div class="col-sm-6">
            <label for="password2" class="form-label">Repetir contraseña *</label>
            <input type="password" class="form-control<?= $inv('password2') ?>" id="password2" name="password2" maxlength="72" autocomplete="new-password" />
            <div class="invalid-feedback"><?= e($errores['password2'] ?? '') ?></div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-acero w-100 py-3">Crear cuenta</button>
            <p class="small text-muted-ac mt-3 mb-0">¿Ya tenés cuenta? <a class="link-acero" href="<?= e(url('/login')) ?>">Iniciá sesión</a></p>
          </div>
        </div>
      </form>
    </div></div>
  </div>
</section>
