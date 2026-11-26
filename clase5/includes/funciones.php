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
