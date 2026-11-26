<?php
// Página actual (ej. "servicios.php") para marcar el ítem activo del menú.
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);

$menu = [
    'index.php'     => 'Inicio',
    'nosotros.php'  => 'Nosotros',
    'servicios.php' => 'Servicios',
    'trabajos.php'  => 'Trabajos',
    'contacto.php'  => 'Contacto',
];
?>
    <header class="site-header">
      <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegación principal">
        <a class="navbar-brand brand" href="index.php">
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
                  href="<?= e($archivo) ?>"
                  <?= $activo ? 'aria-current="page"' : '' ?>
                ><?= e($texto) ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
          <a class="btn btn-acero" href="contacto.php">Pedir Presupuesto <span aria-hidden="true">↗</span></a>
        </div>
      </nav>
    </header>
