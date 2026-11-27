/**
 * TP N° 9 – Comunicación asincrónica con Fetch API y JSON.
 *  1) Trabajos: búsqueda en vivo (sin recargar) y detalle en una ventana modal.
 *  2) Panel de consultas: cambio de estado sin recargar la página.
 * Los datos se insertan con textContent / createElement (nunca con innerHTML) para evitar XSS.
 */
(() => {
  'use strict';

  const BASE = document.body.dataset.base || '';   // '' en el sitio, '../' dentro de /admin/

  function crear(etiqueta, clase, texto) {
    const el = document.createElement(etiqueta);
    if (clase) el.className = clase;
    if (texto !== undefined) el.textContent = texto;
    return el;
  }

  /** Hace la petición y devuelve el JSON; lanza un Error si la respuesta no es exitosa. */
  async function pedirJSON(url, opciones = {}) {
    const respuesta = await fetch(url, {
      ...opciones,
      headers: { Accept: 'application/json', ...(opciones.headers || {}) },
    });

    let cuerpo = null;
    try {
      cuerpo = await respuesta.json();
    } catch (_) {
      /* la respuesta no era JSON */
    }

    if (!respuesta.ok || !cuerpo || cuerpo.status !== 'success') {
      const error = new Error((cuerpo && cuerpo.message) || `Error HTTP ${respuesta.status}`);
      error.codigo = respuesta.status;
      throw error;
    }
    return cuerpo;
  }

  function mensajeDeError(error) {
    return error instanceof TypeError ? 'No hay conexión con el servidor.' : error.message;
  }

  /* ===================================================================
   * 1) BÚSQUEDA DE TRABAJOS (trabajos.php)
   * =================================================================== */
  const formBusqueda = document.getElementById('form-busqueda');

  if (formBusqueda) {
    const inputQ = formBusqueda.querySelector('#q');
    const selectCategoria = formBusqueda.querySelector('#categoria');
    const contenedor = document.getElementById('resultados-trabajos');
    const estado = document.getElementById('estado-busqueda');
    let controlador = null;
    let temporizador = null;

    function crearTarjeta(trabajo) {
      const columna = crear('div', 'col-md-6 col-lg-4 item');

      const enlace = crear('a', 'tarjeta-trabajo text-decoration-none text-reset d-block');
      enlace.href = '#';
      enlace.setAttribute('role', 'button');
      enlace.dataset.id = trabajo.id;
      enlace.setAttribute('aria-label', `Ver detalle de ${trabajo.titulo}`);

      const figura = crear('figure', 'trabajo');
      if (trabajo.imagen) {
        const img = crear('img', 'img-fluid rounded-2 w-100');
        img.src = `${BASE}img/${encodeURIComponent(trabajo.imagen)}`;
        img.width = 600;
        img.height = 420;
        img.loading = 'lazy';
        img.alt = `${trabajo.titulo} (${trabajo.categoria})`;
        figura.appendChild(img);
      } else {
        const vacio = crear('div', 'rounded-2 bg-secondary-subtle w-100');
        vacio.style.aspectRatio = '10 / 7';
        vacio.setAttribute('role', 'img');
        vacio.setAttribute('aria-label', 'Sin imagen');
        figura.appendChild(vacio);
      }

      const leyenda = crear('figcaption');
      leyenda.appendChild(crear('span', '', trabajo.categoria));
      leyenda.appendChild(document.createTextNode(trabajo.titulo));
      figura.appendChild(leyenda);

      enlace.appendChild(figura);
      columna.appendChild(enlace);
      return columna;
    }

    function renderizar(trabajos) {
      contenedor.replaceChildren();

      if (trabajos.length === 0) {
        const aviso = crear('div', 'alert alert-info', 'No encontramos trabajos con esos criterios. ');
        const enlace = crear('a', 'alert-link', 'Ver todos los trabajos');
        enlace.href = 'trabajos.php';
        aviso.append(enlace, '.');
        contenedor.appendChild(aviso);
        return;
      }

      const fila = crear('div', 'row g-4');
      trabajos.forEach((trabajo) => fila.appendChild(crearTarjeta(trabajo)));
      contenedor.appendChild(fila);
    }

    function resumen(total, q, categoriaTexto) {
      if (!q && !categoriaTexto) return '';
      let texto = total === 1 ? 'Se encontró 1 trabajo' : `Se encontraron ${total} trabajos`;
      if (q) texto += ` para «${q}»`;
      if (categoriaTexto) texto += ` en la categoría ${categoriaTexto}`;
      return `${texto}.`;
    }

    async function buscar() {
      const q = inputQ.value.trim();
      const categoria = selectCategoria.value;

      if (controlador) controlador.abort();          // descarta la petición anterior si sigue en curso
      controlador = new AbortController();

      const parametros = new URLSearchParams({ q, categoria });
      contenedor.setAttribute('aria-busy', 'true');
      estado.textContent = 'Buscando…';

      try {
        const res = await pedirJSON(`${BASE}api/buscar_trabajos.php?${parametros}`, {
          signal: controlador.signal,
        });
        renderizar(res.data);

        const categoriaTexto = categoria !== '0' ? selectCategoria.options[selectCategoria.selectedIndex].text : '';
        estado.textContent = resumen(res.total, q, categoriaTexto);

        // Deja la URL compartible (?q=...&categoria=...) sin recargar la página
        const url = new URL(window.location.href);
        url.search = q || categoria !== '0' ? parametros.toString() : '';
        history.replaceState(null, '', url);
      } catch (error) {
        if (error.name === 'AbortError') return;     // una búsqueda más nueva reemplazó a esta
        contenedor.replaceChildren();
        const aviso = crear('div', 'alert alert-danger', `No pudimos cargar los trabajos: ${mensajeDeError(error)}`);
        aviso.setAttribute('role', 'alert');
        contenedor.appendChild(aviso);
        estado.textContent = '';
      } finally {
        contenedor.removeAttribute('aria-busy');
      }
    }

    formBusqueda.addEventListener('submit', (e) => {
      e.preventDefault();
      clearTimeout(temporizador);
      buscar();
    });
    inputQ.addEventListener('input', () => {
      clearTimeout(temporizador);
      temporizador = setTimeout(buscar, 300);        // espera a que el usuario deje de escribir
    });
    selectCategoria.addEventListener('change', buscar);

    /* ---- Detalle de un trabajo en una ventana modal ---- */
    const modalEl = document.getElementById('modalTrabajo');
    const modalTitulo = document.getElementById('modalTrabajoTitulo');
    const modalCuerpo = document.getElementById('modalTrabajoCuerpo');

    function mostrarDetalle(trabajo) {
      modalTitulo.textContent = trabajo.titulo;
      modalCuerpo.replaceChildren();

      if (trabajo.imagen) {
        const img = crear('img', 'img-fluid rounded-2 w-100 mb-3');
        img.src = `${BASE}img/${encodeURIComponent(trabajo.imagen)}`;
        img.alt = `${trabajo.titulo} (${trabajo.categoria})`;
        modalCuerpo.appendChild(img);
      }

      const lista = crear('dl', 'row mb-0');
      const filas = [
        ['Categoría', trabajo.categoria],
        ['Localidad', trabajo.localidad || '—'],
        ['Fecha de la obra', trabajo.fecha_obra || '—'],
        ['Descripción', trabajo.descripcion || 'Sin descripción.'],
      ];
      filas.forEach(([termino, valor]) => {
        lista.appendChild(crear('dt', 'col-sm-4', termino));
        lista.appendChild(crear('dd', 'col-sm-8', valor));
      });
      modalCuerpo.appendChild(lista);
    }

    contenedor.addEventListener('click', async (e) => {
      const enlace = e.target.closest('.tarjeta-trabajo');
      if (!enlace) return;
      e.preventDefault();

      modalTitulo.textContent = 'Detalle del trabajo';
      modalCuerpo.replaceChildren(crear('p', 'text-muted-ac mb-0', 'Cargando…'));
      bootstrap.Modal.getOrCreateInstance(modalEl).show();

      try {
        const res = await pedirJSON(`${BASE}api/trabajo.php?id=${encodeURIComponent(enlace.dataset.id)}`);
        mostrarDetalle(res.data);
      } catch (error) {
        modalCuerpo.replaceChildren(crear('div', 'alert alert-danger mb-0', mensajeDeError(error)));
      }
    });
  }

  /* ===================================================================
   * 2) PANEL DE CONSULTAS: cambiar el estado sin recargar (admin/consultas.php)
   * =================================================================== */
  const tablaConsultas = document.querySelector('table[data-csrf]');

  if (tablaConsultas) {
    const aviso = document.getElementById('mensaje-ajax');

    function mostrarAviso(tipo, texto) {
      aviso.className = `alert alert-${tipo}`;
      aviso.textContent = texto;
    }

    document.querySelectorAll('form[data-ajax="estado"]').forEach((formulario) => {
      formulario.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = Number(formulario.querySelector('[name="id"]').value);
        const estadoId = Number(formulario.querySelector('select').value);
        const boton = formulario.querySelector('button');

        boton.disabled = true;
        try {
          const res = await pedirJSON(`${BASE}api/consulta_estado.php`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-Token': tablaConsultas.dataset.csrf,
            },
            body: JSON.stringify({ id, estado_id: estadoId }),
          });
          mostrarAviso('success', res.message);
        } catch (error) {
          const texto = error.codigo === 401 ? 'Tu sesión venció. Volvé a iniciar sesión.' : mensajeDeError(error);
          mostrarAviso('danger', texto);
        } finally {
          boton.disabled = false;
        }
      });
    });
  }
})();
