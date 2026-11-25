<?php
/**
 * Carga de variables de entorno desde el archivo .env (raíz del proyecto)
 * y helpers globales. Se incluye una sola vez desde includes/header.php.
 */

function cargar_env(string $ruta): void
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
        $_ENV[trim($clave)] = trim($valor, " \t\"'");
    }
}

function env(string $clave, ?string $defecto = null): ?string
{
    return $_ENV[$clave] ?? $defecto;
}

/** Escapa texto para imprimirlo de forma segura en HTML. */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

cargar_env(__DIR__ . '/../.env');

$nombre_sitio   = env('APP_NAME', 'Familia Acero');
$email_contacto = env('APP_EMAIL', 'contacto@example.com');
$whatsapp       = env('WHATSAPP_NUMBER', '5491100000000');
$app_env        = env('APP_ENV', 'local');

if ($app_env === 'local') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
