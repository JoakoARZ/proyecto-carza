<?php
// Estado de la sesión (la sesión ya se inició en header.php)
$logueado = isset($_SESSION['usuario_id']);
$es_admin = $logueado && ($_SESSION['rol'] ?? '') === 'admin';

// Página actual para marcar el ítem activo del menú (el panel /admin/ cuenta como "Administración").
$script        = $_SERVER['SCRIPT_NAME'];
$pagina_actual = str_contains($script, '/admin/') ? 'admin/index.php' : basename($script);

$menu = [
    'index.php'     => 'Inicio',
    'nosotros.php'  => 'Nosotros',
    'servicios.php' => 'Servicios',
    'trabajos.php'  => 'Trabajos',
    'contacto.php'  => 'Contacto',
];
if ($logueado) {
    $menu['perfil.php'] = 'Mi cuenta';
}
if ($es_admin) {
    $menu['admin/index.php'] = 'Administración';
}
?>
    <header class="site-header">
      <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegación principal">
        <a class="navbar-brand brand" href="<?= e($base) ?>index.php">
          <?= e($nombre_sitio) ?>
          <small>Techos &amp; Construcción</small>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#menu"
          aria-controls="menu"
          aria-expanded="false"
          aria-label="Abrir menú"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
          <ul class="navbar-nav mx-auto gap-lg-3">
            <?php foreach ($menu as $archivo => $texto): ?>
              <?php $activo = ($pagina_actual === $archivo); ?>
              <li class="nav-item">
                <a
                  class="nav-link<?= $activo ? ' active' : '' ?>"
                  href="<?= e($base . $archivo) ?>"
                  <?= $activo ? 'aria-current="page"' : '' ?>
                ><?= e($texto) ?></a>
              </li>
            <?php endforeach; ?>
          </ul>

          <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
            <?php if ($logueado): ?>
              <span class="small me-lg-1">Hola, <strong><?= e($_SESSION['usuario_nombre'] ?? '') ?></strong></span>
              <form action="<?= e($base) ?>logout.php" method="post" class="d-inline">
                <?= csrf_campo() ?>
                <button type="submit" class="btn btn-outline-dark btn-sm">Cerrar sesión</button>
              </form>
            <?php else: ?>
              <a class="nav-link<?= $pagina_actual === 'login.php' ? ' active' : '' ?>" href="<?= e($base) ?>login.php">Ingresar</a>
              <a class="nav-link<?= $pagina_actual === 'registro.php' ? ' active' : '' ?>" href="<?= e($base) ?>registro.php">Registrarse</a>
            <?php endif; ?>
            <a class="btn btn-acero" href="<?= e($base) ?>contacto.php">Pedir Presupuesto <span aria-hidden="true">↗</span></a>
          </div>
        </div>
      </nav>
    </header>
