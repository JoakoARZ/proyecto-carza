<?php
// Tabla de rutas: método + URL  ->  [Controlador, acción]
use App\Controllers\AuthController;
use App\Controllers\CuentaController;
use App\Controllers\HomeController;
use App\Controllers\TrabajoController;

/** @var \App\Core\Router $router */

$router->get('/',         [HomeController::class,    'index']);

$router->get('/trabajos', [TrabajoController::class, 'index']);

$router->get('/login',    [AuthController::class,    'mostrarLogin']);
$router->post('/login',   [AuthController::class,    'login']);
$router->get('/registro', [AuthController::class,    'mostrarRegistro']);
$router->post('/registro', [AuthController::class,   'registro']);
$router->post('/logout',  [AuthController::class,    'logout']);

$router->get('/cuenta',   [CuentaController::class,  'perfil']);
