<?php
namespace App\Core;

/** Enrutador mínimo: asocia método + URL con un [Controlador, acción]. */
final class Router
{
    private array $rutas = [];

    /** Carpeta donde vive public/ (ej. /tp10/mvc-nativo/public). */
    public static function base(): string
    {
        return rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    }

    public function get(string $ruta, array $manejador): void
    {
        $this->rutas['GET'][$ruta] = $manejador;
    }

    public function post(string $ruta, array $manejador): void
    {
        $this->rutas['POST'][$ruta] = $manejador;
    }

    public function despachar(string $metodo, string $uri): void
    {
        $ruta = parse_url($uri, PHP_URL_PATH) ?: '/';
        $base = self::base();
        if ($base !== '' && str_starts_with($ruta, $base)) {
            $ruta = substr($ruta, strlen($base));
        }
        $ruta = '/' . trim($ruta, '/');

        $manejador = $this->rutas[$metodo][$ruta] ?? null;

        if ($manejador === null) {
            $existe = isset($this->rutas['GET'][$ruta]) || isset($this->rutas['POST'][$ruta]);
            http_response_code($existe ? 405 : 404);
            View::render($existe ? 'errores/405' : 'errores/404', ['titulo' => $existe ? 'Método no permitido' : 'No encontrado']);
            return;
        }

        [$clase, $accion] = $manejador;
        (new $clase())->$accion();
    }
}
