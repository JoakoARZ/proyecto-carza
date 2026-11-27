<?php
/**
 * Utilidades comunes de los endpoints de /api/.
 * Todas las respuestas son JSON con la forma:
 *   { "status": "success" | "error", "message": "...", "data": ... }
 */
define('ES_API', true);

require_once __DIR__ . '/../includes/funciones.php';   // sesión y helpers de entrada
require_once __DIR__ . '/../config/conexion.php';      // define $pdo

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

/** Envía la respuesta JSON con el código HTTP indicado y termina el script. */
function responder(int $codigo, array $cuerpo): void
{
    http_response_code($codigo);
    echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function responder_error(int $codigo, string $mensaje): void
{
    responder($codigo, ['status' => 'error', 'message' => $mensaje]);
}

/** 405 si el método HTTP no es el esperado. */
function exigir_metodo(string $metodo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== $metodo) {
        header('Allow: ' . $metodo);
        responder_error(405, 'Método no permitido. Usá ' . $metodo . '.');
    }
}

/** Lee el cuerpo de la petición como JSON (400 si no es válido). */
function leer_json(): array
{
    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos)) {
        responder_error(400, 'El cuerpo de la petición debe ser un JSON válido.');
    }
    return $datos;
}

/** 401 si no hay sesión, 403 si el usuario no es administrador. */
function exigir_admin(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        responder_error(401, 'Tenés que iniciar sesión.');
    }
    if (($_SESSION['rol'] ?? '') !== 'admin') {
        responder_error(403, 'No tenés permisos para realizar esta acción.');
    }
}

/** 403 si el token CSRF enviado en la cabecera X-CSRF-Token no coincide con el de la sesión. */
function exigir_csrf_cabecera(): void
{
    $esperado = $_SESSION['csrf'] ?? '';
    $recibido = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if ($esperado === '' || !hash_equals($esperado, $recibido)) {
        responder_error(403, 'No se pudo verificar la solicitud (token inválido).');
    }
}
