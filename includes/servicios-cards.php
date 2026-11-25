<?php
// Partial reutilizado en index.php y servicios.php (evita duplicar las 4 tarjetas).
$tarjetas = [
    ['💧', 'Zinguería y canaletas', 'Canaletas, cenefas y plegados a medida para conducir el agua y proteger tu obra.', 'servicios.php#zingueria'],
    ['🏠', 'Techados y estructuras', 'Techos de chapa, cabriadas y estructuras metálicas resistentes y bien resueltas.', 'servicios.php#zingueria'],
    ['▦', 'Durlock', 'Tabiques, cielorrasos y revestimientos. Espacios funcionales con una terminación prolija.', 'servicios.php#durlock'],
    ['⛩', 'Herrería', 'Portones, rejas, escaleras y pérgolas. Diseño y fabricación para cada necesidad.', 'servicios.php#durlock'],
];
?>
<div class="row g-4">
  <?php foreach ($tarjetas as [$icono, $titulo, $texto, $enlace]): ?>
    <div class="col-sm-6 col-lg-3">
      <article class="card-servicio h-100">
        <span class="icono" aria-hidden="true"><?= $icono ?></span>
        <h3><?= e($titulo) ?></h3>
        <p><?= e($texto) ?></p>
        <a class="link-acero" href="<?= e($enlace) ?>">Conocer más <span class="visually-hidden">sobre <?= e($titulo) ?></span> <span aria-hidden="true">↗</span></a>
      </article>
    </div>
  <?php endforeach; ?>
</div>
