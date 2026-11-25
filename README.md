# Familia Acero – Techos & Construcción

Maquetación web de un emprendimiento familiar de **zinguería, techados, Durlock (construcción en seco) y herrería**. Diseño previo realizado en Figma; maquetado con HTML5, Bootstrap 5 y estilos personalizados.

- **Sitio en vivo:** https://TU-USUARIO.github.io/NOMBRE-DEL-REPO/
- **Repositorio:** https://github.com/TU-USUARIO/NOMBRE-DEL-REPO

## Tecnologías

- HTML5 semántico
- Bootstrap 5.3 (CDN)
- CSS personalizado (`css/styles.css`)
- JavaScript vanilla (`js/main.js`): filtro de galería y validación del formulario
- Prettier (formato de código)
- Lighthouse (auditoría de accesibilidad y rendimiento) – ver [`docs/auditoria-lighthouse.md`](docs/auditoria-lighthouse.md)

## Estructura

```
familia-acero/
├── index.html
├── css/styles.css
├── js/main.js
├── img/
├── docs/auditoria-lighthouse.md
├── package.json
└── README.md
```

## Secciones (10 vistas del diseño)

1. Header / navegación
2. Hero
3. Nuestra historia
4. Servicios (4 tarjetas)
5. Zinguería y techados
6. Durlock y herrería
7. Trabajos realizados (galería con filtros)
8. Paso a paso (proceso)
9. Compromiso + Preguntas frecuentes
10. Contacto (formulario + WhatsApp) y footer

## Flujo de trabajo con Git

- Rama `main`: versión estable y desplegada.
- Rama `feature/maquetacion`: desarrollo de la maqueta.
- Integración mediante Pull Request hacia `main`.

## Cómo correrlo en local

Abrí `index.html` en el navegador, o usá la extensión *Live Server* de VS Code.

```bash
npm install
npm run format
```

## Autor

Tu nombre – Trabajo práctico de maquetación web.
