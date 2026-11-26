<?php
// READ: listado de trabajos
require_once '../includes/funciones.php';
$rol_requerido = 'admin';                       // solo administradores
require_once '../includes/auth_guard.php';
require_once '../config/conexion.php';

$titulo_pagina = 'Administración de trabajos';
$seccion       = 'trabajos';
$flash         = flash_obtener();

$stmt = $pdo->prepare(
    'SELECT t.id, t.titulo, t.publicado, t.fecha_obra,
            c.nombre AS categoria, l.nombre AS localidad
     FROM trabajos t
     INNER JOIN categorias c ON c.id = t.categoria_id
     LEFT JOIN localidades l ON l.id = t.localidad_id
     ORDER BY t.id DESC'
);
$stmt->execute();
$trabajos = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Panel de administración</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Trabajos realizados</h1>
        </div>
      </section>

      <section class="section">
        <div class="container">
          <?php require '_submenu.php'; ?>

          <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['tipo'] === 'exito' ? 'success' : 'danger' ?>" role="<?= $flash['tipo'] === 'exito' ? 'status' : 'alert' ?>">
              <?= e($flash['mensaje']) ?>
            </div>
          <?php endif; ?>

          <a href="crear.php" class="btn btn-acero mb-3">Agregar nuevo trabajo</a>

          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Título</th>
                  <th scope="col">Categoría</th>
                  <th scope="col">Localidad</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$trabajos): ?>
                  <tr><td colspan="7" class="text-center text-muted-ac">Todavía no hay trabajos cargados.</td></tr>
                <?php endif; ?>
                <?php foreach ($trabajos as $item): ?>
                  <tr>
                    <td><?= e((string) $item['id']) ?></td>
                    <td><?= e($item['titulo']) ?></td>
                    <td><?= e($item['categoria']) ?></td>
                    <td><?= e($item['localidad'] ?? '—') ?></td>
                    <td><?= e($item['fecha_obra'] ?? '—') ?></td>
                    <td>
                      <?php if ((int) $item['publicado'] === 1): ?>
                        <span class="badge text-bg-success">Publicado</span>
                      <?php else: ?>
                        <span class="badge text-bg-secondary">Oculto</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-nowrap">
                      <a href="editar.php?id=<?= (int) $item['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                      <form action="eliminar.php" method="post" class="d-inline"
                            onsubmit="return confirm('¿Confirma eliminar este registro?')">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>" />
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
<?php require_once '../includes/footer.php'; ?>
