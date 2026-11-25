# Auditoría Lighthouse

Herramienta: Lighthouse (Chrome DevTools). Modo: Navegación. Dispositivo: Móvil y Escritorio.

## Resultados iniciales (antes de corregir)

| Categoría     | Móvil | Escritorio |
| ------------- | ----- | ---------- |
| Rendimiento   | \_\_  | \_\_       |
| Accesibilidad | \_\_  | \_\_       |
| Prácticas     | \_\_  | \_\_       |
| SEO           | \_\_  | \_\_       |

> Completar con tus valores reales y adjuntar captura en `docs/` (ej. `docs/lighthouse-antes.png`).

## Fallas de accesibilidad detectadas y correcciones

| #   | Falla detectada                                                   | Corrección aplicada                                  |
| --- | ----------------------------------------------------------------- | ---------------------------------------------------- |
| 1   | (ej. Contraste insuficiente en textos color óxido)                | Se oscureció el color a `#b3411f` (ratio > 4.5:1)    |
| 2   | (ej. Imágenes sin `alt`)                                          | Se agregaron textos alternativos descriptivos        |
| 3   | (ej. Campos de formulario sin `label`)                            | Se asociaron `label for` a cada `input`              |
| 4   | (ej. Enlaces sin texto discernible / `target="_blank"` sin aviso) | Se agregó texto oculto y `rel="noopener noreferrer"` |
| 5   | (ej. Sin enlace "Saltar al contenido")                            | Se agregó `skip-link`                                |

> Reemplazá la columna "Falla detectada" por lo que realmente te marque Lighthouse.

## Resultados finales (después de corregir)

| Categoría     | Móvil | Escritorio |
| ------------- | ----- | ---------- |
| Rendimiento   | \_\_  | \_\_       |
| Accesibilidad | \_\_  | \_\_       |
| Prácticas     | \_\_  | \_\_       |
| SEO           | \_\_  | \_\_       |

## Formato del código

Se aplicó Prettier con `npx prettier --write .` (configuración en `.prettierrc`).
