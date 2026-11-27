<?php
require_once 'includes/funciones.php';
require_once 'config/conexion.php';

// Un usuario con sesión iniciada no necesita registrarse
if (isset($_SESSION['usuario_id'])) {
    redirigir('perfil.php');
}

$titulo_pagina      = 'Crear cuenta';
$descripcion_pagina = 'Registrate para seguir el estado de tus consultas de presupuesto.';

$valores = ['nombre' => '', 'apellido' => '', 'email' => ''];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Captura y sanitización (las contraseñas se leen sin modificarlas y nunca se muestran de vuelta)
    $valores['nombre']   = obtener_post('nombre');
    $valores['apellido'] = obtener_post('apellido');
    $valores['email']    = mb_strtolower(obtener_post('email'));
    $password            = obtener_post_sin_recorte('password');
    $password2           = obtener_post_sin_recorte('password2');

    // 2) Validación en servidor
    if (!csrf_valido()) {
        $errores['general'] = 'La sesión del formulario venció. Recargá la página e intentá de nuevo.';
    }

    foreach (['nombre' => 'nombre', 'apellido' => 'apellido'] as $campo => $etiqueta) {
        $largo = mb_strlen($valores[$campo]);
        if ($valores[$campo] === '') {
            $errores[$campo] = "Ingresá tu $etiqueta.";
        } elseif ($largo < 2 || $largo > 60) {
            $errores[$campo] = "El $etiqueta debe tener entre 2 y 60 caracteres.";
        } elseif (!preg_match('/^[\p{L}\s\'.-]+$/u', $valores[$campo])) {
            $errores[$campo] = "El $etiqueta solo puede contener letras y espacios.";
        }
    }

    if ($valores['email'] === '') {
        $errores['email'] = 'Ingresá tu correo electrónico.';
    } elseif (mb_strlen($valores['email']) > 120 || !filter_var($valores['email'], FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Ingresá un correo válido (ej. nombre@dominio.com).';
    }

    if ($password === '') {
        $errores['password'] = 'Ingresá una contraseña.';
    } elseif (strlen($password) < 8) {
        $errores['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (strlen($password) > 72) {            // bcrypt solo usa los primeros 72 bytes
        $errores['password'] = 'La contraseña no puede superar los 72 caracteres.';
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $errores['password'] = 'La contraseña debe incluir al menos una letra y un número.';
    }

    if ($password2 === '' || !hash_equals($password, $password2)) {
        $errores['password2'] = 'Las contraseñas no coinciden.';
    }

    // 3) El correo no debe existir previamente
    if (!isset($errores['email']) && !isset($errores['general'])) {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $valores['email']]);
        if ($stmt->fetch()) {
            $errores['email'] = 'Ese correo ya está registrado. Probá iniciar sesión.';
        }
    }

    // 4) Alta con la contraseña encriptada (bcrypt) y consulta preparada
    if (!$errores) {
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                "INSERT INTO usuarios (rol_id, nombre, apellido, email, password_hash)
                 VALUES ((SELECT id FROM roles WHERE nombre = 'usuario'), :nombre, :apellido, :email, :hash)"
            );
            $stmt->execute([
                ':nombre'   => $valores['nombre'],
                ':apellido' => $valores['apellido'],
                ':email'    => $valores['email'],
                ':hash'     => $hash,
            ]);

            flash('exito', 'Tu cuenta se creó correctamente. Ya podés iniciar sesión.');
            redirigir('login.php');
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') {      // violación de UNIQUE (correo duplicado)
                $errores['email'] = 'Ese correo ya está registrado. Probá iniciar sesión.';
            } else {
                error_log('Error al registrar usuario: ' . $ex->getMessage());
                $errores['general'] = 'No pudimos crear tu cuenta por un problema técnico. Verificá que exista el rol "usuario" (sql/migracion_tp8.sql).'
                    . ($app_env === 'local' ? ' (' . $ex->getMessage() . ')' : '');
            }
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
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Crear cuenta</h1>
        </div>
      </section>

      <section class="section section-alt">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <form class="form-card" method="post" novalidate>
                <?= csrf_campo() ?>
                <p class="text-muted-ac small">Los campos con * son obligatorios.</p>

                <?php if ($errores): ?>
                  <div class="alert alert-danger" role="alert">
                    <strong>No pudimos crear tu cuenta.</strong> Revisá estos datos:
                    <ul class="mb-0 mt-2">
                      <?php foreach ($errores as $mensaje_error): ?>
                        <li><?= e($mensaje_error) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>

                <div class="row g-3">
                  <div class="col-sm-6">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control<?= clase_invalida($errores, 'nombre') ?>" id="nombre" name="nombre"
                           value="<?= e($valores['nombre']) ?>" maxlength="60" autocomplete="given-name" <?= atributos_error($errores, 'nombre') ?> />
                    <div class="invalid-feedback" id="error-nombre"><?= e($errores['nombre'] ?? '') ?></div>
                  </div>
                  <div class="col-sm-6">
                    <label for="apellido" class="form-label">Apellido *</label>
                    <input type="text" class="form-control<?= clase_invalida($errores, 'apellido') ?>" id="apellido" name="apellido"
                           value="<?= e($valores['apellido']) ?>" maxlength="60" autocomplete="family-name" <?= atributos_error($errores, 'apellido') ?> />
                    <div class="invalid-feedback" id="error-apellido"><?= e($errores['apellido'] ?? '') ?></div>
                  </div>
                  <div class="col-12">
                    <label for="email" class="form-label">Correo electrónico *</label>
                    <input type="email" class="form-control<?= clase_invalida($errores, 'email') ?>" id="email" name="email"
                           value="<?= e($valores['email']) ?>" maxlength="120" autocomplete="email" <?= atributos_error($errores, 'email') ?> />
                    <div class="invalid-feedback" id="error-email"><?= e($errores['email'] ?? '') ?></div>
                  </div>
                  <div class="col-sm-6">
                    <label for="password" class="form-label">Contraseña *</label>
                    <input type="password" class="form-control<?= clase_invalida($errores, 'password') ?>" id="password" name="password"
                           maxlength="72" autocomplete="new-password" aria-describedby="ayuda-password error-password"
                           <?= isset($errores['password']) ? 'aria-invalid="true"' : '' ?> />
                    <div class="invalid-feedback" id="error-password"><?= e($errores['password'] ?? '') ?></div>
                    <div class="form-text" id="ayuda-password">Mínimo 8 caracteres, con letras y números.</div>
                  </div>
                  <div class="col-sm-6">
                    <label for="password2" class="form-label">Repetir contraseña *</label>
                    <input type="password" class="form-control<?= clase_invalida($errores, 'password2') ?>" id="password2" name="password2"
                           maxlength="72" autocomplete="new-password" <?= atributos_error($errores, 'password2') ?> />
                    <div class="invalid-feedback" id="error-password2"><?= e($errores['password2'] ?? '') ?></div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-acero w-100 py-3">Crear cuenta</button>
                    <p class="small text-muted-ac mt-3 mb-0">¿Ya tenés cuenta? <a class="link-acero" href="login.php">Iniciá sesión</a></p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
