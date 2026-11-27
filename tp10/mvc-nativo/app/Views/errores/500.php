<section class="section"><div class="container text-center py-5">
  <p class="eyebrow">Error 500</p>
  <h1 class="display-6 fw-bold">Error del servidor</h1>
  <p class="text-muted-ac">Ocurrió un problema inesperado. Probá de nuevo en unos minutos.</p>
  <?php if (!empty($detalle)): ?><p class="small text-muted-ac"><code><?= e($detalle) ?></code></p><?php endif; ?>
  <a class="btn btn-acero" href="<?= e(url('/')) ?>">Volver al inicio</a>
</div></section>
