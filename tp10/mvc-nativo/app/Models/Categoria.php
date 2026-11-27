<?php
namespace App\Models;

use App\Core\Database;
use PDO;

/** Modelo de la tabla categorias. Solo contiene consultas SQL (PDO preparado). */
final class Categoria
{
    /** @return array<int,string> id => nombre */
    public static function todas(): array
    {
        $stmt = Database::conexion()->prepare('SELECT id, nombre FROM categorias ORDER BY nombre');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
