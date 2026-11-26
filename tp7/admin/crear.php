<?php
// CREATE: alta de un trabajo
require_once '../includes/funciones.php';
require_once '../config/conexion.php';

$titulo_pagina = 'Nuevo trabajo';
$seccion       = 'trabajos';

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

$datos = [
    'titulo' => '', 'descripcion' => '', 'categoria_id' => '', 'localidad_id' => '',
    'imagen' => '', 'fecha_obra' => '', 'publicado' => '1',
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
            $sql = 'INSERT INTO trabajos (categoria_id, usuario_id, localidad_id, titulo, descripcion, imagen, publicado, fecha_obra)
                    VALUES (:categoria_id, (SELECT id FROM usuarios WHERE email = :admin_email), :localidad_id,
                            :titulo, :descripcion, :imagen, :publicado, :fecha_obra)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(parametros_trabajo($datos) + [':admin_email' => env('ADMIN_EMAIL', 'admin@carza.com')]);

            flash('exito', 'El trabajo se creó correctamente.');
            redirigir('index.php');
        } catch (PDOException $ex) {
            error_log('Error al crear trabajo: ' . $ex->getMessage());
            $errores['general'] = 'No se pudo guardar el trabajo. Verificá que exista el usuario administrador.'
                . ($app_env === 'local' ? ' (' . $ex->getMessage() . ')' : '');
        }
    }
}

$texto_boton = 'Guardar trabajo';
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Panel de administración</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Nuevo trabajo</h1>
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
