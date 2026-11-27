<?php
namespace App\Core;

/** Controlador base: utilidades compartidas por todos los controladores. */
abstract class Controller
{
    protected function vista(string $vista, array $datos = [], int $codigo = 200): void
    {
        http_response_code($codigo);
        View::render($vista, $datos);
    }

    protected function redirigir(string $ruta): void
    {
        header('Location: ' . url($ruta));
        exit;
    }

    /** Lee un campo de $_POST o $_GET con filter_input() y lo sanitiza. */
    private function entrada(int $tipo, string $campo, bool $recortar): string
    {
        $valor = filter_input($tipo, $campo, FILTER_UNSAFE_RAW);
        if (!is_string($valor)) {
            return '';
        }
        $valor = preg_replace('/[^\P{C}\n\r\t]/u', '', $valor) ?? '';   // quita caracteres de control
        return $recortar ? trim($valor) : $valor;
    }

    protected function post(string $campo, bool $recortar = true): string
    {
        return $this->entrada(INPUT_POST, $campo, $recortar);
    }

    protected function get(string $campo): string
    {
        return $this->entrada(INPUT_GET, $campo, true);
    }

    protected function exigirLogin(): void
    {
        if (Sesion::usuarioId() === null) {
            $this->redirigir('/login?error=acceso_denegado');
        }
    }

    protected function exigirCsrf(): void
    {
        if (!Sesion::csrfValido($this->post('csrf'))) {
            $this->vista('errores/419', ['titulo' => 'Sesión vencida'], 419);
            exit;
        }
    }
}
