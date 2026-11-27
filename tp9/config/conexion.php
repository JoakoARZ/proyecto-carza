<?php
/**
 * Conexión PDO a MySQL con manejo de excepciones.
 * Los datos salen del archivo .env (ver .env.example).
 * Define la variable $pdo, que usan las páginas que incluyen este archivo.
 */
require_once __DIR__ . '/env.php';

$db_host    = env('DB_HOST', '127.0.0.1');
$db_port    = env('DB_PORT', '3306');
$db_nombre  = env('DB_NAME', 'carza_db');
$db_usuario = env('DB_USER', 'root');
$db_clave   = env('DB_PASS', '');
$charset    = 'utf8mb4';

$dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_nombre;charset=$charset";

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,   // consultas preparadas reales del motor
];

try {
    $pdo = new PDO($dsn, $db_usuario, $db_clave, $opciones);
} catch (PDOException $e) {
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());
    http_response_code(500);
    if (defined('ES_API')) {                       // los endpoints de /api/ responden siempre en JSON
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode(['status' => 'error', 'message' => 'No pudimos conectar con la base de datos.']));
    }
    $detalle = ($app_env === 'local') ? '<br><small>' . e($e->getMessage()) . '</small>' : '';
    die('<p style="font-family:sans-serif;padding:2rem">No pudimos conectar con la base de datos.' . $detalle . '</p>');
}
