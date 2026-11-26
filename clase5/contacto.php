<?php
require_once 'includes/funciones.php';

$titulo_pagina      = 'Contacto y presupuesto';
$descripcion_pagina = 'Pedí tu presupuesto sin cargo. Escribinos por WhatsApp o completá el formulario.';

// ---------- Formulario por POST: procesamiento y validación en el servidor ----------
$tipos_trabajo = ['Zinguería y canaletas', 'Techados y estructuras', 'Durlock', 'Herrería', 'Otro'];
$campos        = ['nombre', 'telefono', 'email', 'tipo', 'mensaje'];

$valores  = array_fill_keys($campos, '');
$errores  = [];
$enviado  = false;
$resumen  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Captura + sanitización (trim, caracteres de control)
    foreach ($campos as $campo) {
        $valores[$campo] = obtener_post($campo);
    }

    // 2) Validación en servidor
    if ($valores['nombre'] === '') {
        $errores['nombre'] = 'Ingresá tu nombre.';
    } elseif (mb_strlen($valores['nombre']) < 2 || mb_strlen($valores['nombre']) > 80) {
        $errores['nombre'] = 'El nombre debe tener entre 2 y 80 caracteres.';
    } elseif (!preg_match('/^[\p{L}\s\'.-]+$/u', $valores['nombre'])) {
        $errores['nombre'] = 'El nombre solo puede contener letras y espacios.';
    }

    if ($valores['telefono'] === '') {
        $errores['telefono'] = 'Ingresá un teléfono de contacto.';
    } elseif (!preg_match('/^\+?[0-9\s()-]{6,20}$/', $valores['telefono'])) {
        $errores['telefono'] = 'Ingresá un teléfono válido (solo números, espacios, + y guiones).';
    }

    if ($valores['email'] === '') {
        $errores['email'] = 'Ingresá tu correo electrónico.';
    } elseif (!filter_var($valores['email'], FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Ingresá un correo válido (ej. nombre@dominio.com).';
    }

    if ($valores['tipo'] === '') {
        $errores['tipo'] = 'Elegí un tipo de trabajo.';
    } elseif (!in_array($valores['tipo'], $tipos_trabajo, true)) {
        $errores['tipo'] = 'El tipo de trabajo seleccionado no es válido.';
    }

    if (mb_strlen($valores['mensaje']) > 500) {
        $errores['mensaje'] = 'El mensaje no puede superar los 500 caracteres.';
    }

    // 3) Resultado: éxito (se limpia el formulario) o error (se conservan los datos)
    if (!$errores) {
        $enviado = true;
        $resumen = $valores;
        $valores = array_fill_keys($campos, '');
    }
}

$preguntas = [
    ['¿Hacen trabajos a medida?', 'Sí. Relevamos las medidas y las necesidades de tu obra para fabricar cada pieza y ajustar la solución a tu espacio.'],
    ['¿Incluyen materiales o solo mano de obra?', 'Podemos trabajar de las dos maneras. En el presupuesto te detallamos qué materiales incluye y qué alcance tiene el trabajo.'],
    ['¿Qué zonas cubren?', 'Trabajamos en nuestra localidad y zonas cercanas. Consultanos por la tuya y te confirmamos.'],
    ['¿El presupuesto tiene costo?', 'No. La visita, la medición y el presupuesto son sin cargo y sin compromiso.'],
    ['¿Qué garantía ofrecen?', 'Revisamos los detalles al terminar y acompañamos el resultado. Los plazos de garantía se acuerdan por escrito en cada trabajo.'],
];

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>
    <main id="contenido">
      <section class="page-banner" aria-labelledby="pagina-titulo">
        <div class="container">
          <p class="eyebrow eyebrow-light">Empecemos tu proyecto</p>
          <h1 id="pagina-titulo" class="display-5 fw-bold text-white">Contanos qué necesitás hacer.</h1>
        </div>
      </section>

      <section class="section section-alt" aria-labelledby="form-titulo">
        <div class="container">
          <div class="row g-5">
            <div class="col-lg-6">
              <p class="text-muted-ac fs-5">
                Una idea, una reparación o una obra desde cero.<br />Te ayudamos a encontrar la mejor solución.
              </p>
              <a class="btn btn-dark-ac btn-lg" href="https://wa.me/<?= e($whatsapp) ?>" target="_blank" rel="noopener noreferrer">
                Escribinos por WhatsApp <span aria-hidden="true">↗</span><span class="visually-hidden"> (se abre en una pestaña nueva)</span>
              </a>
              <p class="small text-muted-ac mt-3">Mandanos fotos y medidas aproximadas para orientarte mejor desde el primer contacto.</p>
              <p class="mt-4 mb-1">También por correo: <a class="link-acero" href="mailto:<?= e($email_contacto) ?>"><?= e($email_contacto) ?></a></p>
              <p class="eyebrow mt-5">Sin cargo · Sin compromiso</p>
            </div>

            <div class="col-lg-6">
              <form class="form-card" id="formulario" method="post" action="contacto.php#formulario" novalidate>
                <h2 id="form-titulo" class="h4 fw-bold">Pedí tu presupuesto</h2>
                <p class="text-muted-ac small">Dejanos tus datos y contanos sobre el trabajo. Los campos con * son obligatorios.</p>

                <?php if ($enviado): ?>
                  <div class="alert alert-success" role="status">
                    <strong>¡Gracias, <?= e($resumen['nombre']) ?>!</strong> Recibimos tu consulta y te vamos a contactar a la brevedad.
                    <dl class="row small mb-0 mt-3">
                      <dt class="col-sm-4">Teléfono</dt><dd class="col-sm-8"><?= e($resumen['telefono']) ?></dd>
                      <dt class="col-sm-4">Correo</dt><dd class="col-sm-8"><?= e($resumen['email']) ?></dd>
                      <dt class="col-sm-4">Trabajo</dt><dd class="col-sm-8"><?= e($resumen['tipo']) ?></dd>
                      <?php if ($resumen['mensaje'] !== ''): ?>
                        <dt class="col-sm-4">Mensaje</dt><dd class="col-sm-8"><?= nl2br(e($resumen['mensaje'])) ?></dd>
                      <?php endif; ?>
                    </dl>
                  </div>
                <?php elseif ($errores): ?>
                  <div class="alert alert-danger" role="alert">
                    <strong>No pudimos enviar tu consulta.</strong> Revisá estos datos:
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
                           value="<?= e($valores['nombre']) ?>" placeholder="Tu nombre" autocomplete="name" maxlength="80"
                           <?= atributos_error($errores, 'nombre') ?> />
                    <div class="invalid-feedback" id="error-nombre"><?= e($errores['nombre'] ?? '') ?></div>
                  </div>
                  <div class="col-sm-6">
                    <label for="telefono" class="form-label">Teléfono *</label>
                    <input type="tel" class="form-control<?= clase_invalida($errores, 'telefono') ?>" id="telefono" name="telefono"
                           value="<?= e($valores['telefono']) ?>" placeholder="Código de área + número" autocomplete="tel" maxlength="20"
                           <?= atributos_error($errores, 'telefono') ?> />
                    <div class="invalid-feedback" id="error-telefono"><?= e($errores['telefono'] ?? '') ?></div>
                  </div>
                  <div class="col-12">
                    <label for="email" class="form-label">Correo electrónico *</label>
                    <input type="email" class="form-control<?= clase_invalida($errores, 'email') ?>" id="email" name="email"
                           value="<?= e($valores['email']) ?>" placeholder="nombre@dominio.com" autocomplete="email" maxlength="120"
                           <?= atributos_error($errores, 'email') ?> />
                    <div class="invalid-feedback" id="error-email"><?= e($errores['email'] ?? '') ?></div>
                  </div>
                  <div class="col-12">
                    <label for="tipo" class="form-label">Tipo de trabajo *</label>
                    <select class="form-select<?= clase_invalida($errores, 'tipo') ?>" id="tipo" name="tipo" <?= atributos_error($errores, 'tipo') ?>>
                      <option value="" <?= $valores['tipo'] === '' ? 'selected' : '' ?> disabled>Seleccioná un servicio</option>
                      <?php foreach ($tipos_trabajo as $tipo): ?>
                        <option value="<?= e($tipo) ?>" <?= $valores['tipo'] === $tipo ? 'selected' : '' ?>><?= e($tipo) ?></option>
                      <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="error-tipo"><?= e($errores['tipo'] ?? '') ?></div>
                  </div>
                  <div class="col-12">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control<?= clase_invalida($errores, 'mensaje') ?>" id="mensaje" name="mensaje" rows="4" maxlength="600"
                              placeholder="¿Qué tenés en mente? Contanos los detalles..."
                              <?= atributos_error($errores, 'mensaje') ?>><?= e($valores['mensaje']) ?></textarea>
                    <div class="invalid-feedback" id="error-mensaje"><?= e($errores['mensaje'] ?? '') ?></div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-acero w-100 py-3">Solicitar presupuesto <span aria-hidden="true">↗</span></button>
                    <p class="small text-muted-ac mt-2 mb-0">Usamos tus datos únicamente para responder tu consulta.</p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>

      <section class="section" aria-labelledby="faq-titulo">
        <div class="container">
          <div class="row g-5">
            <div class="col-lg-5">
              <p class="eyebrow">Preguntas frecuentes</p>
              <h2 id="faq-titulo" class="display-6 fw-bold">Antes de empezar, resolvemos tus dudas.</h2>
              <p class="text-muted-ac mt-4">Cada obra es distinta. Si tu consulta no está acá, estamos para ayudarte.</p>
            </div>
            <div class="col-lg-7">
              <div class="accordion accordion-flush" id="faqAcc">
                <?php foreach ($preguntas as $i => [$pregunta, $respuesta]): ?>
                  <div class="accordion-item">
                    <h3 class="accordion-header">
                      <button class="accordion-button<?= $i > 0 ? ' collapsed' : '' ?>" type="button" data-bs-toggle="collapse"
                              data-bs-target="#faq<?= $i ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="faq<?= $i ?>"><?= e($pregunta) ?></button>
                    </h3>
                    <div id="faq<?= $i ?>" class="accordion-collapse collapse<?= $i === 0 ? ' show' : '' ?>" data-bs-parent="#faqAcc">
                      <div class="accordion-body"><?= e($respuesta) ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
