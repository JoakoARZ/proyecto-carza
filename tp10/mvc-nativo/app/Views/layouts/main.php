<?php use App\Core\Env; use App\Core\Sesion; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($titulo ?? 'CARZA') ?> | <?= e(Env::get('APP_NAME', 'CARZA')) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= e(asset('css/styles.css')) ?>" />
</head>
<body>
  <a class="visually-hidden-focusable skip-link" href="#contenido">Saltar al contenido</a>

  <header class="site-header">
    <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegación principal">
      <a class="navbar-brand brand" href="<?= e(url('/')) ?>"><?= e(Env::get('APP_NAME', 'CARZA')) ?><small>Arquitectura MVC</small></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
              aria-controls="menu" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mx-auto gap-lg-3">
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/')) ?>">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/trabajos')) ?>">Trabajos</a></li>
          <?php if (Sesion::usuarioId() !== null): ?>
            <li class="nav-item"><a class="nav-link" href="<?= e(url('/cuenta')) ?>">Mi cuenta</a></li>
          <?php endif; ?>
        </ul>
        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
          <?php if (Sesion::usuarioId() !== null): ?>
            <span class="small">Hola, <strong><?= e(Sesion::usuarioNombre()) ?></strong></span>
            <form action="<?= e(url('/logout')) ?>" method="post" class="d-inline">
              <?= csrf_campo() ?>
              <button type="submit" class="btn btn-outline-dark btn-sm">Cerrar sesión</button>
            </form>
          <?php else: ?>
            <a class="nav-link" href="<?= e(url('/login')) ?>">Ingresar</a>
            <a class="btn btn-acero" href="<?= e(url('/registro')) ?>">Registrarse</a>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>

  <main id="contenido">
    <?php if (!empty($flash)): ?>
      <div class="container pt-4">
        <div class="alert alert-<?= $flash['tipo'] === 'exito' ? 'success' : 'danger' ?> mb-0" role="status"><?= e($flash['mensaje']) ?></div>
      </div>
    <?php endif; ?>
    <?= $contenido ?>
  </main>

  <footer class="site-footer">
    <div class="container py-4 small">
      © <?= date('Y') ?> <?= e(Env::get('APP_NAME', 'CARZA')) ?> · Versión MVC nativa (PHP puro)
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
