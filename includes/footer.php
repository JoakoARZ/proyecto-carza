    <footer class="site-footer" aria-labelledby="footer-titulo">
      <div class="container py-5">
        <h2 id="footer-titulo" class="visually-hidden">Información de contacto</h2>
        <div class="row g-5">
          <div class="col-lg-4">
            <p class="brand brand-light mb-3"><?= e($nombre_sitio) ?><small>Techos &amp; Construcción</small></p>
            <p class="text-white-90">El valor de un trabajo bien hecho.<br />La confianza de una familia.</p>
            <p class="d-flex gap-3 mb-0">
              <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span><span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
              <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">Facebook <span aria-hidden="true">↗</span><span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
            </p>
          </div>
          <div class="col-lg-4">
            <h3 class="footer-label">Contacto directo</h3>
            <p class="mb-3">
              <strong>WhatsApp</strong><br />
              <a href="https://wa.me/<?= e($whatsapp) ?>" target="_blank" rel="noopener noreferrer">+<?= e($whatsapp) ?><span class="visually-hidden"> (se abre en una pestaña nueva)</span></a><br />
              <a href="mailto:<?= e($email_contacto) ?>"><?= e($email_contacto) ?></a>
            </p>
            <h3 class="footer-label">Horario de atención</h3>
            <p class="mb-0 text-white-90">Lunes a viernes · 8 a 18 h<br />Sábados · 8 a 13 h</p>
          </div>
          <div class="col-lg-4">
            <h3 class="footer-label">Cobertura</h3>
            <div class="mapa" role="img" aria-label="Mapa de la zona de cobertura (ilustrativo)"><span class="pin"></span></div>
            <a href="contacto.php" class="d-inline-block mt-2">Consultá por tu localidad <span aria-hidden="true">↗</span></a>
          </div>
        </div>
        <hr class="footer-line" />
        <div class="d-flex justify-content-between flex-wrap gap-2 small text-white-75">
          <span>© <?= date('Y') ?> <?= e($nombre_sitio) ?> · Emprendimiento familiar</span>
          <a href="#contenido">Volver arriba <span aria-hidden="true">↑</span></a>
        </div>
        <?php if ($app_env === 'local'): ?>
          <p class="small text-white-75 mt-4 mb-0">Entorno: <?= e($app_env) ?></p>
        <?php endif; ?>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
