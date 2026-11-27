<?php
/**
 * Funciones de entrada y validación (TP N° 5).
 * Toda variable que llega por $_POST o $_GET se lee con estas funciones
 * y se imprime siempre con e() (htmlspecialchars) para prevenir XSS.
 */

/**
 * Lee un campo de $_POST o $_GET con filter_input() y lo sanitiza:
 *  - descarta valores que no sean texto (ej. arrays),
 *  - elimina caracteres de control (conserva saltos de línea y tabulaciones),
 *  - recorta espacios con trim().
 */
function obtener_entrada(int $tipo, string $campo): string
{
    $valor = filter_input($tipo, $campo, FILTER_UNSAFE_RAW);
    if (!is_string($valor)) {
        return '';
    }
    $valor = preg_replace('/[^\P{C}\n\r\t]/u', '', $valor) ?? '';
    return trim($valor);
}

function obtener_post(string $campo): string
{
    return obtener_entrada(INPUT_POST, $campo);
}

function obtener_get(string $campo): string
{
    return obtener_entrada(INPUT_GET, $campo);
}

/** Devuelve " is-invalid" si el campo tiene error (para Bootstrap). */
function clase_invalida(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? ' is-invalid' : '';
}

/** Atributos de accesibilidad para un campo con error. */
function atributos_error(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? 'aria-invalid="true" aria-describedby="error-' . $campo . '"' : '';
}

/* =====================================================================
 * TP N° 7 – Sesión, mensajes flash, protección CSRF y validación de trabajos
 * ===================================================================== */

// La sesión se inicia acá: estas funciones se incluyen antes de imprimir HTML.
if (session_status() === PHP_SESSION_NONE) {
    // Cookie de sesión segura: no accesible desde JavaScript, solo por cookies, SameSite y Secure bajo HTTPS
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Token anti-CSRF: evita que otro sitio envíe formularios en nombre del usuario. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '" />';
}

function csrf_valido(): bool
{
    $token = obtener_post('csrf');
    return $token !== '' && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/** Mensaje de una sola vez (se muestra después de redirigir). */
function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function flash_obtener(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function redirigir(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/** Lee y sanitiza los campos del formulario de trabajos (POST). */
function leer_trabajo_post(): array
{
    return [
        'titulo'       => obtener_post('titulo'),
        'descripcion'  => obtener_post('descripcion'),
        'categoria_id' => obtener_post('categoria_id'),
        'localidad_id' => obtener_post('localidad_id'),
        'imagen'       => obtener_post('imagen'),
        'fecha_obra'   => obtener_post('fecha_obra'),
        'publicado'    => obtener_post('publicado') === '1' ? '1' : '0',
    ];
}

/** Validación en servidor de un trabajo. Devuelve un arreglo campo => mensaje. */
function validar_trabajo(array $d, array $ids_categorias, array $ids_localidades): array
{
    $err = [];

    $largo = mb_strlen($d['titulo']);
    if ($d['titulo'] === '') {
        $err['titulo'] = 'Ingresá el título del trabajo.';
    } elseif ($largo < 3 || $largo > 120) {
        $err['titulo'] = 'El título debe tener entre 3 y 120 caracteres.';
    }

    if (mb_strlen($d['descripcion']) > 1000) {
        $err['descripcion'] = 'La descripción no puede superar los 1000 caracteres.';
    }

    if ($d['categoria_id'] === '') {
        $err['categoria_id'] = 'Elegí una categoría.';
    } elseif (!ctype_digit($d['categoria_id']) || !in_array((int) $d['categoria_id'], $ids_categorias, true)) {
        $err['categoria_id'] = 'La categoría elegida no es válida.';
    }

    if ($d['localidad_id'] !== ''
        && (!ctype_digit($d['localidad_id']) || !in_array((int) $d['localidad_id'], $ids_localidades, true))) {
        $err['localidad_id'] = 'La localidad elegida no es válida.';
    }

    if ($d['imagen'] !== '' && !preg_match('/^[\w-]{1,100}\.(jpg|jpeg|png|webp)$/i', $d['imagen'])) {
        $err['imagen'] = 'Ingresá solo el nombre del archivo (ej. galeria-1.jpg).';
    }

    if ($d['fecha_obra'] !== '') {
        $fecha = DateTime::createFromFormat('!Y-m-d', $d['fecha_obra']);
        if (!$fecha || $fecha->format('Y-m-d') !== $d['fecha_obra']) {
            $err['fecha_obra'] = 'La fecha no es válida.';
        }
    }

    return $err;
}

/** Convierte los datos validados en parámetros nombrados para PDO. */
function parametros_trabajo(array $d): array
{
    return [
        ':categoria_id' => (int) $d['categoria_id'],
        ':localidad_id' => $d['localidad_id'] !== '' ? (int) $d['localidad_id'] : null,
        ':titulo'       => $d['titulo'],
        ':descripcion'  => $d['descripcion'] !== '' ? $d['descripcion'] : null,
        ':imagen'       => $d['imagen'] !== '' ? $d['imagen'] : null,
        ':publicado'    => (int) $d['publicado'],
        ':fecha_obra'   => $d['fecha_obra'] !== '' ? $d['fecha_obra'] : null,
    ];
}

/** Lee un campo POST sin aplicar trim() (las contraseñas no se modifican). */
function obtener_post_sin_recorte(string $campo): string
{
    $valor = filter_input(INPUT_POST, $campo, FILTER_UNSAFE_RAW);
    return is_string($valor) ? $valor : '';
}
