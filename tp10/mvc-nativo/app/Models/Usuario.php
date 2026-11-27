<?php
namespace App\Models;

use App\Core\Database;

/** Modelo de la tabla usuarios (con su rol). */
final class Usuario
{
    public static function porEmail(string $email): ?array
    {
        $stmt = Database::conexion()->prepare(
            'SELECT u.id, u.nombre, u.apellido, u.email, u.password_hash, u.activo, r.nombre AS rol
             FROM usuarios u
             INNER JOIN roles r ON r.id = u.rol_id
             WHERE u.email = :email'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public static function porId(int $id): ?array
    {
        $stmt = Database::conexion()->prepare(
            'SELECT u.id, u.nombre, u.apellido, u.email, u.created_at, r.nombre AS rol
             FROM usuarios u
             INNER JOIN roles r ON r.id = u.rol_id
             WHERE u.id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function existeEmail(string $email): bool
    {
        $stmt = Database::conexion()->prepare('SELECT 1 FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    /** Crea un usuario con rol "usuario". La contraseña se guarda con bcrypt. */
    public static function crear(string $nombre, string $apellido, string $email, string $password): int
    {
        $pdo  = Database::conexion();
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (rol_id, nombre, apellido, email, password_hash)
             VALUES ((SELECT id FROM roles WHERE nombre = 'usuario'), :nombre, :apellido, :email, :hash)"
        );
        $stmt->execute([
            ':nombre'   => $nombre,
            ':apellido' => $apellido,
            ':email'    => $email,
            ':hash'     => password_hash($password, PASSWORD_BCRYPT),
        ]);
        return (int) $pdo->lastInsertId();
    }
}
