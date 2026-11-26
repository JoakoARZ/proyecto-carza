<?php
require_once __DIR__ . '/../config/env.php';

// Cada página define $titulo_pagina (y opcionalmente $descripcion_pagina) antes de incluir esta plantilla.
$titulo_pagina      = $titulo_pagina ?? 'Inicio';
$descripcion_pagina = $descripcion_pagina
    ?? 'Emprendimiento familiar de techos, zinguería, Durlock y herrería a medida. Pedí tu presupuesto sin cargo.';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= e($titulo_pagina) ?> | <?= e($nombre_sitio) ?></title>
    <meta name="description" content="<?= e($descripcion_pagina) ?>" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/styles.css" />
  </head>
  <body>
    <a class="visually-hidden-focusable skip-link" href="#contenido">Saltar al contenido</a>
