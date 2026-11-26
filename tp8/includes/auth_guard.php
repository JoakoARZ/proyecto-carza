<?php
/**
 * Guardián de rutas. Se incluye al comienzo de cada página protegida:
 *
 *   $rol_requerido = 'admin';                 // opcional: restringe por rol
 *   require_once 'includes/auth_guard.php';
 *
 *  - Sin sesión iniciada  -> login.php?error=acceso_denegado
 *  - Con sesión pero sin el rol requerido -> perfil.php con un aviso
 */
require_once __DIR__ . '/funciones.php';   // inicia la sesión

$prefijo_ruta = str_contains($_SERVER['SCRIPT_NAME'], '/admin/') ? '../' : '';

if (!isset($_SESSION['usuario_id'])) {
    redirigir($prefijo_ruta . 'login.php?error=acceso_denegado');
}

if (isset($rol_requerido) && ($_SESSION['rol'] ?? '') !== $rol_requerido) {
    flash('error', 'No tenés permisos para acceder a esa sección.');
    redirigir($prefijo_ruta . 'perfil.php');
}
