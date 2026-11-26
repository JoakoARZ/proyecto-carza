<?php
// Consultas de presupuesto recibidas por el formulario de contacto: leer, cambiar estado y eliminar
require_once '../includes/funciones.php';
$rol_requerido = 'admin';                       // solo administradores
require_once '../includes/auth_guard.php';
require_once '../config/conexion.php';

$titulo_pagina = 'Consultas recibidas';
$seccion       = 'consultas';

$estados = $pdo->prepare('SELECT id, nombre FROM estados_consulta ORDER BY id');
$estados->execute();
$estados = $estados->fetchAll(PDO::FETCH_KEY_PAIR);

// ---------- Acciones por POST (UPDATE de estado y DELETE) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = obtener_post('accion');
    $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!csrf_valido() || !$id) {
        flash('error', 'No se pudo verificar la solicitud. Intentá de nuevo.');
        redirigir('consultas.php');
    }

    try {
        if ($accion === 'estado') {
            $estado_id = filter_input(INPUT_POST, 'estado_id', FILTER_VALIDATE_INT);
            if (!$estado_id || !array_key_exists($estado_id, $estados)) {
                flash('error', 'El estado elegido no es válido.');
            } else {
                $stmt = $pdo->prepare('UPDATE consultas SET estado_id = :estado_id WHERE id = :id');
                $stmt->execute([':estado_id' => $estado_id, ':id' => $id]);
                flash('exito', 'El estado de la consulta se actualizó.');
            }
        } elseif ($accion === 'eliminar') {
            $stmt = $pdo->prepare('DELETE FROM consultas WHERE id = :id');
            $stmt->execute([':id' => $id]);
            flash('exito', 'La consulta se eliminó correctamente.');
        } else {
            flash('error', 'La acción solicitada no es válida.');
        }
    } catch (PDOException $ex) {
        error_log('Error en consultas: ' . $ex->getMessage());
        flash('error', 'No se pudo completar la operación.');
    }
    redirigir('consultas.php');
}

$flash = flash_obtener();

// ---------- Filtro opcional por estado (GET) ----------
$filtro_estado = filter_input(INPUT_GET, 'estado', FILTER_VALIDATE_INT);
if (!$filtro_estado || !array_key_exists($filtro_estado, $estados)) {
    $filtro_estado = 0;
}

$sql = "SELECT c.id, c.nombre, c.telefono, c.email, c.mensaje, c.created_at,
               c.estado_id, e.nombre AS estado, l.nombre AS localidad,
               GROUP_CONCAT(cat.nombre ORDER BY cat.nombre SEPARATOR ', ') AS rubros
        FROM consultas c
        INNER JOIN estados_consulta e ON e.id = c.estado_id
        LEFT JOIN localidades l ON l.id = c.localidad_id
        LEFT JOIN consulta_categoria cc ON cc.consulta_id = c.id
        LEFT JOIN categorias cat ON cat.id = cc.categoria_id";
$params = [];
if ($filtro_estado) {
    $sql .= ' WHERE c.estado_id = :estado';
    $params[':estado'] = $filtro_estado;
}
$sql .= ' GROUP BY c.id, c.nombre, c.telefono, c.email, c.mensaje, c.created_at, c.estado_id, e.nombre, l.nombre
          ORDER BY c.created_at DESC, c.id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$consultas = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Panel de administración</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Consultas recibidas</h1>
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

          <form class="row g-2 align-items-end mb-3" method="get" role="search" aria-label="Filtrar consultas por estado">
            <div class="col-sm-6 col-md-4">
              <label for="estado" class="form-label">Filtrar por estado</label>
              <select class="form-select" id="estado" name="estado">
                <option value="0">Todos</option>
                <?php foreach ($estados as $id_estado => $nombre_estado): ?>
                  <option value="<?= (int) $id_estado ?>" <?= $filtro_estado === (int) $id_estado ? 'selected' : '' ?>><?= e($nombre_estado) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-auto d-flex gap-2">
              <button type="submit" class="btn btn-acero">Filtrar</button>
              <a class="btn btn-outline-dark" href="consultas.php">Limpiar</a>
            </div>
          </form>

          <p class="text-muted-ac" role="status"><?= count($consultas) ?> consulta(s) encontrada(s).</p>

          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead>
                <tr>
                  <th scope="col">Fecha</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Contacto</th>
                  <th scope="col">Rubros</th>
                  <th scope="col">Localidad</th>
                  <th scope="col">Mensaje</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$consultas): ?>
                  <tr><td colspan="8" class="text-center text-muted-ac">No hay consultas para mostrar.</td></tr>
                <?php endif; ?>
                <?php foreach ($consultas as $c): ?>
                  <tr>
                    <td class="text-nowrap"><?= e($c['created_at']) ?></td>
                    <td><?= e($c['nombre']) ?></td>
                    <td><?= e($c['telefono']) ?><br /><small><?= e($c['email']) ?></small></td>
                    <td><?= e($c['rubros'] ?? '—') ?></td>
                    <td><?= e($c['localidad'] ?? '—') ?></td>
                    <td><?= e(mb_strimwidth($c['mensaje'] ?? '', 0, 120, '…')) ?></td>
                    <td>
                      <form method="post" class="d-flex gap-1">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="accion" value="estado" />
                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>" />
                        <label class="visually-hidden" for="estado-<?= (int) $c['id'] ?>">Estado de la consulta de <?= e($c['nombre']) ?></label>
                        <select class="form-select form-select-sm select-estado" id="estado-<?= (int) $c['id'] ?>" name="estado_id">
                          <?php foreach ($estados as $id_estado => $nombre_estado): ?>
                            <option value="<?= (int) $id_estado ?>" <?= (int) $c['estado_id'] === (int) $id_estado ? 'selected' : '' ?>><?= e($nombre_estado) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-warning">Guardar</button>
                      </form>
                    </td>
                    <td>
                      <form method="post" onsubmit="return confirm('¿Confirma eliminar este registro?')">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="accion" value="eliminar" />
                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>" />
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
