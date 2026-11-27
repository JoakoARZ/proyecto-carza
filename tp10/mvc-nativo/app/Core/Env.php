<?php
namespace App\Core;

/** Lee las variables del archivo .env. */
final class Env
{
    private static array $datos = [];

    public static function cargar(string $ruta): void
    {
        if (!is_readable($ruta)) {
            return;
        }
        foreach (file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
            $linea = trim($linea);
            if ($linea === '' || $linea[0] === '#' || !str_contains($linea, '=')) {
                continue;
            }
            [$clave, $valor] = explode('=', $linea, 2);
            self::$datos[trim($clave)] = trim($valor, " \t\"'");
        }
    }

    public static function get(string $clave, ?string $defecto = null): ?string
    {
        return self::$datos[$clave] ?? $defecto;
    }
}
