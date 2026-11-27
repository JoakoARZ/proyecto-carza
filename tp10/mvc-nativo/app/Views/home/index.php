<section class="page-banner">
  <div class="container">
    <p class="eyebrow eyebrow-light">Refactorización</p>
    <h1 class="display-5 fw-bold text-white">Arquitectura MVC en PHP puro</h1>
  </div>
</section>
<section class="section">
  <div class="container">
    <p class="fs-5 text-muted-ac">Los módulos de <strong>catálogo de trabajos</strong> y <strong>autenticación</strong> reorganizados en Modelos, Vistas y Controladores.</p>
    <div class="row g-4 mt-2">
      <div class="col-md-4"><div class="card-servicio h-100"><h2 class="h5">Modelos</h2><p>Clases que solo contienen las consultas SQL con PDO y consultas preparadas (<code>app/Models</code>).</p></div></div>
      <div class="col-md-4"><div class="card-servicio h-100"><h2 class="h5">Controladores</h2><p>Reciben la solicitud, validan, llaman al modelo y eligen la vista (<code>app/Controllers</code>).</p></div></div>
      <div class="col-md-4"><div class="card-servicio h-100"><h2 class="h5">Vistas</h2><p>Plantillas HTML sin SQL ni lógica de negocio (<code>app/Views</code>).</p></div></div>
    </div>
    <div class="d-flex gap-2 mt-4">
      <a class="btn btn-acero" href="<?= e(url('/trabajos')) ?>">Ver catálogo</a>
      <a class="btn btn-outline-dark" href="<?= e(url('/login')) ?>">Iniciar sesión</a>
    </div>
  </div>
</section>
