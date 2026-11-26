<?php
// Cierre de sesión (solo por POST con token CSRF, desde el botón "Cerrar sesión")
require_once 'includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
    redirigir('index.php');
}

// 1) Vaciar las variables de sesión
$_SESSION = [];
session_unset();

// 2) Eliminar la cookie de sesión del navegador
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 42000,
        'path'     => $p['path'],
        'domain'   => $p['domain'],
        'secure'   => $p['secure'],
        'httponly' => $p['httponly'],
        'samesite' => $p['samesite'] ?: 'Lax',
    ]);
}

// 3) Destruir la sesión en el servidor
session_destroy();

redirigir('login.php?mensaje=sesion_cerrada');
