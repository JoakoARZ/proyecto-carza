<?php
require_once 'includes/funciones.php';
require_once 'config/conexion.php';

// Si ya hay sesión, ir directo al panel que corresponde
if (isset($_SESSION['usuario_id'])) {
    redirigir(($_SESSION['rol'] ?? '') === 'admin' ? 'admin/index.php' : 'perfil.php');
}

$titulo_pagina      = 'Iniciar sesión';
$descripcion_pagina = 'Ingresá a tu cuenta para ver el estado de tus consultas.';

// Hash de relleno para que el tiempo de respuesta sea similar aunque el correo no exista
const HASH_RELLENO = '$2y$10$aaaaaaaaaaaaaaaaaaaaaubbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb';

$email   = '';
$errores = [];
$flash   = flash_obtener();

// Avisos por parámetro GET (solo valores conocidos)
$aviso = null;
switch (obtener_get('error')) {
    case 'acceso_denegado':
        $aviso = ['warning', 'Tenés que iniciar sesión para acceder a esa página.'];
        break;
}
if (obtener_get('mensaje') === 'sesion_cerrada') {
    $aviso = ['success', 'Cerraste sesión correctamente.'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mb_strtolower(obtener_post('email'));
    $password = obtener_post_sin_recorte('password');

    if (!csrf_valido()) {
        $errores['general'] = 'La sesión del formulario venció. Recargá la página e intentá de nuevo.';
    } elseif ($email === '' || $password === '') {
        $errores['general'] = 'Ingresá tu correo y tu contraseña.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'SELECT u.id, u.nombre, u.password_hash, u.activo, r.nombre AS rol
                 FROM usuarios u
                 INNER JOIN roles r ON r.id = u.rol_id
                 WHERE u.email = :email'
            );
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            // password_verify() compara la contraseña ingresada con el hash guardado
            $coincide = password_verify($password, $usuario['password_hash'] ?? HASH_RELLENO);

            if ($usuario && $coincide && (int) $usuario['activo'] === 1) {
                session_regenerate_id(true);              // evita la fijación de sesión
                unset($_SESSION['csrf']);                 // token nuevo para la sesión nueva
                $_SESSION['usuario_id']     = (int) $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['rol']            = $usuario['rol'];

                flash('exito', 'Bienvenido/a, ' . $usuario['nombre'] . '.');
                redirigir($usuario['rol'] === 'admin' ? 'admin/index.php' : 'perfil.php');
            }

            // Mensaje genérico: no revela si el correo existe o no
            $errores['general'] = 'Correo o contraseña incorrectos.';
        } catch (PDOException $ex) {
            error_log('Error en login: ' . $ex->getMessage());
            $errores['general'] = 'No pudimos iniciar sesión por un problema técnico.'
                . ($app_env === 'local' ? ' (' . $ex->getMessage() . ')' : '');
        }
    }
}

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Tu cuenta</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Iniciar sesión</h1>
        </div>
      </section>

      <section class="section section-alt">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
              <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['tipo'] === 'exito' ? 'success' : 'danger' ?>" role="status"><?= e($flash['mensaje']) ?></div>
              <?php endif; ?>
              <?php if ($aviso): ?>
                <div class="alert alert-<?= e($aviso[0]) ?>" role="alert"><?= e($aviso[1]) ?></div>
              <?php endif; ?>

              <form class="form-card" method="post" novalidate>
                <?= csrf_campo() ?>

                <?php if ($errores): ?>
                  <div class="alert alert-danger" role="alert"><?= e($errores['general'] ?? '') ?></div>
                <?php endif; ?>

                <div class="mb-3">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>"
                         maxlength="120" autocomplete="email" required />
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="password" name="password"
                         maxlength="72" autocomplete="current-password" required />
                </div>
                <button type="submit" class="btn btn-acero w-100 py-3">Ingresar</button>
                <p class="small text-muted-ac mt-3 mb-0">¿No tenés cuenta? <a class="link-acero" href="registro.php">Registrate</a></p>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
