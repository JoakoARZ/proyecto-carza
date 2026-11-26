<?php
// Submenú del panel. Cada página define $seccion = 'trabajos' o 'consultas' antes de incluirlo.
$seccion = $seccion ?? 'trabajos';
?>
<ul class="nav nav-pills mb-4" aria-label="Secciones del panel">
  <li class="nav-item">
    <a class="nav-link<?= $seccion === 'trabajos' ? ' active' : '' ?>" href="index.php"<?= $seccion === 'trabajos' ? ' aria-current="page"' : '' ?>>Trabajos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?= $seccion === 'consultas' ? ' active' : '' ?>" href="consultas.php"<?= $seccion === 'consultas' ? ' aria-current="page"' : '' ?>>Consultas recibidas</a>
  </li>
</ul>
