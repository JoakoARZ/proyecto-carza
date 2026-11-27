<section class="page-banner"><div class="container">
  <p class="eyebrow eyebrow-light">Tu cuenta</p><h1 class="display-5 fw-bold text-white">Iniciar sesión</h1>
</div></section>

<section class="section section-alt">
  <div class="container">
    <div class="row justify-content-center"><div class="col-md-8 col-lg-5">
      <?php if (!empty($aviso)): ?>
        <div class="alert alert-<?= e($aviso[0]) ?>" role="alert"><?= e($aviso[1]) ?></div>
      <?php endif; ?>

      <form class="form-card" method="post" action="<?= e(url('/login')) ?>" novalidate>
        <?= csrf_campo() ?>
        <?php if ($error !== ''): ?><div class="alert alert-danger" role="alert"><?= e($error) ?></div><?php endif; ?>
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" maxlength="120" autocomplete="email" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" maxlength="72" autocomplete="current-password" required />
        </div>
        <button type="submit" class="btn btn-acero w-100 py-3">Ingresar</button>
        <p class="small text-muted-ac mt-3 mb-0">¿No tenés cuenta? <a class="link-acero" href="<?= e(url('/registro')) ?>">Registrate</a></p>
      </form>
    </div></div>
  </div>
</section>
