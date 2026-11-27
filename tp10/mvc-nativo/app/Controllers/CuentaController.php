<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sesion;
use App\Models\Usuario;

/** Página protegida: requiere sesión iniciada. */
final class CuentaController extends Controller
{
    public function perfil(): void
    {
        $this->exigirLogin();

        $usuario = Usuario::porId((int) Sesion::usuarioId());
        if ($usuario === null) {               // la cuenta fue eliminada mientras había sesión
            Sesion::salir();
            $this->redirigir('/login?error=acceso_denegado');
        }

        $this->vista('cuenta/perfil', ['titulo' => 'Mi cuenta', 'usuario' => $usuario]);
    }
}
