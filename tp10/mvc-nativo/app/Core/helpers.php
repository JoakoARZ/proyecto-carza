<?php
// Funciones auxiliares disponibles en las vistas.
use App\Core\Router;
use App\Core\Sesion;

/** Escapa texto para imprimirlo en HTML (previene XSS). */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/** URL absoluta (desde la raíz del sitio) para una ruta de la aplicación. */
function url(string $ruta = '/'): string
{
    return Router::base() . '/' . ltrim($ruta, '/');
}

/** URL de un archivo estático dentro de public/. */
function asset(string $ruta): string
{
    return Router::base() . '/' . ltrim($ruta, '/');
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(Sesion::csrfToken()) . '" />';
}
