<?php
$titulo_pagina      = 'Contacto y presupuesto';
$descripcion_pagina = 'Pedí tu presupuesto sin cargo. Escribinos por WhatsApp o completá el formulario.';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$preguntas = [
    ['¿Hacen trabajos a medida?', 'Sí. Relevamos las medidas y las necesidades de tu obra para fabricar cada pieza y ajustar la solución a tu espacio.'],
    ['¿Incluyen materiales o solo mano de obra?', 'Podemos trabajar de las dos maneras. En el presupuesto te detallamos qué materiales incluye y qué alcance tiene el trabajo.'],
    ['¿Qué zonas cubren?', 'Trabajamos en nuestra localidad y zonas cercanas. Consultanos por la tuya y te confirmamos.'],
    ['¿El presupuesto tiene costo?', 'No. La visita, la medición y el presupuesto son sin cargo y sin compromiso.'],
    ['¿Qué garantía ofrecen?', 'Revisamos los detalles al terminar y acompañamos el resultado. Los plazos de garantía se acuerdan por escrito en cada trabajo.'],
];
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
              <form class="form-card" id="form-presupuesto" novalidate>
                <h2 id="form-titulo" class="h4 fw-bold">Pedí tu presupuesto</h2>
                <p class="text-muted-ac small">Dejanos tus datos y contanos sobre el trabajo.</p>
                <div class="row g-3">
                  <div class="col-sm-6">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" autocomplete="name" required />
                    <div class="invalid-feedback">Ingresá tu nombre.</div>
                  </div>
                  <div class="col-sm-6">
                    <label for="telefono" class="form-label">Teléfono *</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Código de área + número" autocomplete="tel" required />
                    <div class="invalid-feedback">Ingresá un teléfono de contacto.</div>
                  </div>
                  <div class="col-12">
                    <label for="tipo" class="form-label">Tipo de trabajo *</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                      <option value="" selected disabled>Seleccioná un servicio</option>
                      <option>Zinguería y canaletas</option>
                      <option>Techados y estructuras</option>
                      <option>Durlock</option>
                      <option>Herrería</option>
                      <option>Otro</option>
                    </select>
                    <div class="invalid-feedback">Elegí un tipo de trabajo.</div>
                  </div>
                  <div class="col-12">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="¿Qué tenés en mente? Contanos los detalles..."></textarea>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-acero w-100 py-3">Solicitar presupuesto <span aria-hidden="true">↗</span></button>
                    <p class="small text-muted-ac mt-2 mb-0">Usamos tus datos únicamente para responder tu consulta.</p>
                    <p id="form-ok" class="alert alert-success mt-3 mb-0 d-none" role="status">¡Gracias! Recibimos tu consulta y te vamos a contactar a la brevedad.</p>
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
