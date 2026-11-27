<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sesion;
use App\Models\Usuario;

/** Registro, inicio y cierre de sesión. */
final class AuthController extends Controller
{
    // Hash de relleno para igualar el tiempo de respuesta cuando el correo no existe
    private const HASH_RELLENO = '$2y$10$aaaaaaaaaaaaaaaaaaaaaubbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb';

    public function mostrarLogin(): void
    {
        if (Sesion::usuarioId() !== null) {
            $this->redirigir('/cuenta');
        }

        $aviso = null;
        if ($this->get('error') === 'acceso_denegado') {
            $aviso = ['warning', 'Tenés que iniciar sesión para acceder a esa página.'];
        }
        if ($this->get('mensaje') === 'sesion_cerrada') {
            $aviso = ['success', 'Cerraste sesión correctamente.'];
        }

        $this->vista('auth/login', ['titulo' => 'Iniciar sesión', 'email' => '', 'error' => '', 'aviso' => $aviso]);
    }

    public function login(): void
    {
        $this->exigirCsrf();

        $email    = mb_strtolower($this->post('email'));
        $password = $this->post('password', false);
        $error    = '';

        if ($email === '' || $password === '') {
            $error = 'Ingresá tu correo y tu contraseña.';
        } else {
            $usuario  = Usuario::porEmail($email);
            $coincide = password_verify($password, $usuario['password_hash'] ?? self::HASH_RELLENO);

            if ($usuario && $coincide && (int) $usuario['activo'] === 1) {
                Sesion::entrar($usuario);
                Sesion::flash('exito', 'Bienvenido/a, ' . $usuario['nombre'] . '.');
                $this->redirigir('/cuenta');
            }
            $error = 'Correo o contraseña incorrectos.';
        }

        $this->vista('auth/login', ['titulo' => 'Iniciar sesión', 'email' => $email, 'error' => $error, 'aviso' => null]);
    }

    public function mostrarRegistro(): void
    {
        if (Sesion::usuarioId() !== null) {
            $this->redirigir('/cuenta');
        }
        $this->vista('auth/registro', [
            'titulo' => 'Crear cuenta',
            'valores' => ['nombre' => '', 'apellido' => '', 'email' => ''],
            'errores' => [],
        ]);
    }

    public function registro(): void
    {
        $this->exigirCsrf();

        $valores = [
            'nombre'   => $this->post('nombre'),
            'apellido' => $this->post('apellido'),
            'email'    => mb_strtolower($this->post('email')),
        ];
        $password  = $this->post('password', false);
        $password2 = $this->post('password2', false);

        $errores = $this->validarRegistro($valores, $password, $password2);

        if (!$errores) {
            Usuario::crear($valores['nombre'], $valores['apellido'], $valores['email'], $password);
            Sesion::flash('exito', 'Tu cuenta se creó correctamente. Ya podés iniciar sesión.');
            $this->redirigir('/login');
        }

        $this->vista('auth/registro', ['titulo' => 'Crear cuenta', 'valores' => $valores, 'errores' => $errores]);
    }

    public function logout(): void
    {
        $this->exigirCsrf();
        Sesion::salir();
        $this->redirigir('/login?mensaje=sesion_cerrada');
    }

    /** Validación en servidor del formulario de registro. */
    private function validarRegistro(array $v, string $password, string $password2): array
    {
        $err = [];

        foreach (['nombre', 'apellido'] as $campo) {
            $largo = mb_strlen($v[$campo]);
            if ($v[$campo] === '') {
                $err[$campo] = "Ingresá tu $campo.";
            } elseif ($largo < 2 || $largo > 60) {
                $err[$campo] = "El $campo debe tener entre 2 y 60 caracteres.";
            } elseif (!preg_match('/^[\p{L}\s\'.-]+$/u', $v[$campo])) {
                $err[$campo] = "El $campo solo puede contener letras y espacios.";
            }
        }

        if ($v['email'] === '') {
            $err['email'] = 'Ingresá tu correo electrónico.';
        } elseif (mb_strlen($v['email']) > 120 || !filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
            $err['email'] = 'Ingresá un correo válido (ej. nombre@dominio.com).';
        } elseif (Usuario::existeEmail($v['email'])) {
            $err['email'] = 'Ese correo ya está registrado. Probá iniciar sesión.';
        }

        if ($password === '') {
            $err['password'] = 'Ingresá una contraseña.';
        } elseif (strlen($password) < 8) {
            $err['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        } elseif (strlen($password) > 72) {
            $err['password'] = 'La contraseña no puede superar los 72 caracteres.';
        } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            $err['password'] = 'La contraseña debe incluir al menos una letra y un número.';
        }

        if ($password2 === '' || !hash_equals($password, $password2)) {
            $err['password2'] = 'Las contraseñas no coinciden.';
        }

        return $err;
    }
}
