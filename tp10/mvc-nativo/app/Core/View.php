<?php
namespace App\Core;

/** Motor de vistas: renderiza una plantilla dentro del layout principal. */
final class View
{
    public static function render(string $vista, array $datos = [], string $layout = 'layouts/main'): void
    {
        $flash = Sesion::flashObtener();
        extract($datos, EXTR_SKIP);                   // cada clave del arreglo pasa a ser una variable

        ob_start();
        require self::ruta($vista);
        $contenido = ob_get_clean();

        require self::ruta($layout);
    }

    private static function ruta(string $vista): string
    {
        return dirname(__DIR__) . '/Views/' . $vista . '.php';
    }
}
