<?php
// Página protegida para cualquier usuario con sesión iniciada
require_once 'includes/auth_guard.php';
require_once 'config/conexion.php';

$titulo_pagina = 'Mi cuenta';
$flash         = flash_obtener();

$stmt = $pdo->prepare(
    'SELECT u.nombre, u.apellido, u.email, u.created_at, r.nombre AS rol
     FROM usuarios u
     INNER JOIN roles r ON r.id = u.rol_id
     WHERE u.id = :id'
);
$stmt->execute([':id' => (int) $_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if (!$usuario) {                       // la cuenta fue eliminada mientras había sesión
    $_SESSION = [];
    redirigir('login.php?error=acceso_denegado');
}

// Consultas de presupuesto enviadas con esta cuenta
$stmt = $pdo->prepare(
    "SELECT c.id, c.created_at, c.mensaje, e.nombre AS estado,
            GROUP_CONCAT(cat.nombre ORDER BY cat.nombre SEPARATOR ', ') AS rubros
     FROM consultas c
     INNER JOIN estados_consulta e ON e.id = c.estado_id
     LEFT JOIN consulta_categoria cc ON cc.consulta_id = c.id
     LEFT JOIN categorias cat ON cat.id = cc.categoria_id
     WHERE c.usuario_id = :id
     GROUP BY c.id, c.created_at, c.mensaje, e.nombre
     ORDER BY c.created_at DESC, c.id DESC"
);
$stmt->execute([':id' => (int) $_SESSION['usuario_id']]);
$consultas = $stmt->fetchAll();

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Mi cuenta</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Hola, <?= e($usuario['nombre']) ?></h1>
        </div>
      </section>

      <section class="section">
        <div class="container">
          <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['tipo'] === 'exito' ? 'success' : 'danger' ?>" role="<?= $flash['tipo'] === 'exito' ? 'status' : 'alert' ?>"><?= e($flash['mensaje']) ?></div>
          <?php endif; ?>

          <div class="row g-5">
            <div class="col-lg-4">
              <h2 class="h4 fw-bold">Mis datos</h2>
              <dl class="mt-3">
                <dt>Nombre</dt><dd><?= e($usuario['nombre'] . ' ' . $usuario['apellido']) ?></dd>
                <dt>Correo</dt><dd><?= e($usuario['email']) ?></dd>
                <dt>Rol</dt><dd><span class="badge text-bg-secondary"><?= e($usuario['rol']) ?></span></dd>
                <dt>Miembro desde</dt><dd><?= e($usuario['created_at']) ?></dd>
              </dl>
              <?php if ($usuario['rol'] === 'admin'): ?>
                <a class="btn btn-dark-ac" href="admin/index.php">Ir al panel de administración</a>
              <?php endif; ?>
            </div>

            <div class="col-lg-8">
              <h2 class="h4 fw-bold">Mis consultas de presupuesto</h2>
              <?php if (!$consultas): ?>
                <p class="text-muted-ac mt-3">Todavía no enviaste consultas con tu cuenta. <a class="link-acero" href="contacto.php">Pedí tu presupuesto</a>.</p>
              <?php else: ?>
                <div class="table-responsive mt-3">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr><th scope="col">Fecha</th><th scope="col">Rubros</th><th scope="col">Mensaje</th><th scope="col">Estado</th></tr>
                    </thead>
                    <tbody>
                      <?php foreach ($consultas as $c): ?>
                        <tr>
                          <td class="text-nowrap"><?= e($c['created_at']) ?></td>
                          <td><?= e($c['rubros'] ?? '—') ?></td>
                          <td><?= e(mb_strimwidth($c['mensaje'] ?? '', 0, 100, '…')) ?></td>
                          <td><span class="badge text-bg-warning"><?= e($c['estado']) ?></span></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
