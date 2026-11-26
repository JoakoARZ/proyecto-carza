<?php
// UPDATE: edición de un trabajo existente
require_once '../includes/funciones.php';
require_once '../config/conexion.php';

$titulo_pagina = 'Editar trabajo';
$seccion       = 'trabajos';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    flash('error', 'El identificador del trabajo no es válido.');
    redirigir('index.php');
}

$stmt = $pdo->prepare('SELECT * FROM trabajos WHERE id = :id');
$stmt->execute([':id' => $id]);
$fila = $stmt->fetch();
if (!$fila) {
    flash('error', 'El trabajo que intentás editar no existe.');
    redirigir('index.php');
}

$categorias  = $pdo->prepare('SELECT id, nombre FROM categorias ORDER BY nombre');
$categorias->execute();
$categorias  = $categorias->fetchAll(PDO::FETCH_KEY_PAIR);

$localidades = $pdo->prepare(
    "SELECT l.id, CONCAT(l.nombre, ' (', p.nombre, ')') AS texto
     FROM localidades l INNER JOIN provincias p ON p.id = l.provincia_id
     ORDER BY p.nombre, l.nombre"
);
$localidades->execute();
$localidades = $localidades->fetchAll(PDO::FETCH_KEY_PAIR);

// Valores iniciales: lo que hay guardado en la base
$datos = [
    'titulo'       => $fila['titulo'],
    'descripcion'  => $fila['descripcion'] ?? '',
    'categoria_id' => (string) $fila['categoria_id'],
    'localidad_id' => $fila['localidad_id'] !== null ? (string) $fila['localidad_id'] : '',
    'imagen'       => $fila['imagen'] ?? '',
    'fecha_obra'   => $fila['fecha_obra'] ?? '',
    'publicado'    => (string) (int) $fila['publicado'],
];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = leer_trabajo_post();

    if (!csrf_valido()) {
        $errores['general'] = 'La sesión del formulario venció. Recargá la página e intentá de nuevo.';
    } else {
        $errores = validar_trabajo($datos, array_keys($categorias), array_keys($localidades));
    }

    if (!$errores) {
        try {
            $sql = 'UPDATE trabajos
                    SET categoria_id = :categoria_id, localidad_id = :localidad_id, titulo = :titulo,
                        descripcion = :descripcion, imagen = :imagen, publicado = :publicado, fecha_obra = :fecha_obra
                    WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(parametros_trabajo($datos) + [':id' => $id]);

            flash('exito', 'El trabajo se actualizó correctamente.');
            redirigir('index.php');
        } catch (PDOException $ex) {
            error_log('Error al editar trabajo: ' . $ex->getMessage());
            $errores['general'] = 'No se pudo actualizar el trabajo.'
                . ($app_env === 'local' ? ' (' . $ex->getMessage() . ')' : '');
        }
    }
}

$texto_boton = 'Guardar cambios';
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Panel de administración</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Editar trabajo #<?= (int) $id ?></h1>
        </div>
      </section>
      <section class="section">
        <div class="container">
          <?php require '_submenu.php'; ?>
          <div class="row"><div class="col-lg-8">
            <?php require '_form_trabajo.php'; ?>
          </div></div>
        </div>
      </section>
    </main>
<?php require_once '../includes/footer.php'; ?>
