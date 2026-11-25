// Filtro de la galería de trabajos
const botones = document.querySelectorAll(".btn-filtro");
const items = document.querySelectorAll("#galeria .item");

botones.forEach((boton) => {
  boton.addEventListener("click", () => {
    const filtro = boton.dataset.filter;

    botones.forEach((b) => {
      b.classList.remove("active");
      b.setAttribute("aria-pressed", "false");
    });
    boton.classList.add("active");
    boton.setAttribute("aria-pressed", "true");

    items.forEach((item) => {
      const visible = filtro === "todos" || item.dataset.cat === filtro;
      item.classList.toggle("oculto", !visible);
    });
  });
});

// Validación simple del formulario de presupuesto
const form = document.getElementById("form-presupuesto");
const ok = document.getElementById("form-ok");

form.addEventListener("submit", (e) => {
  e.preventDefault();
  ok.classList.add("d-none");

  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return;
  }

  // Maqueta: acá se conectaría con un backend o servicio de formularios.
  form.reset();
  form.classList.remove("was-validated");
  ok.classList.remove("d-none");
});
