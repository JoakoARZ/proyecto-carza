<?php
declare(strict_types=1);

use App\Core\Env;
use App\Core\Router;
use App\Core\Sesion;
use App\Core\View;

// Autocarga: App\Models\Trabajo -> app/Models/Trabajo.php
spl_autoload_register(function (string $clase): void {
    $prefijo = 'App\\';
    if (!str_starts_with($clase, $prefijo)) {
        return;
    }
    $ruta = __DIR__ . '/' . str_replace('\\', '/', substr($clase, strlen($prefijo))) . '.php';
    if (is_file($ruta)) {
        require $ruta;
    }
});

require __DIR__ . '/Core/helpers.php';

Env::cargar(dirname(__DIR__) . '/.env');
Sesion::iniciar();

$router = new Router();
require __DIR__ . '/routes.php';

try {
    $router->despachar($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    View::render('errores/500', [
        'titulo'  => 'Error del servidor',
        'detalle' => Env::get('APP_ENV') === 'local' ? $e->getMessage() : '',
    ]);
}
