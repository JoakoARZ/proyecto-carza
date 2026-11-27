<?php
namespace App\Models;

use App\Core\Database;

/** Modelo de la tabla trabajos (catálogo de trabajos realizados). */
final class Trabajo
{
    /**
     * Trabajos publicados, con filtros opcionales por palabra clave y categoría.
     * @return array<int,array<string,mixed>>
     */
    public static function publicados(string $q = '', int $categoriaId = 0): array
    {
        $sql = 'SELECT t.id, t.titulo, t.descripcion, t.imagen, c.nombre AS categoria
                FROM trabajos t
                INNER JOIN categorias c ON c.id = t.categoria_id
                WHERE t.publicado = 1';
        $params = [];

        if ($categoriaId > 0) {
            $sql .= ' AND t.categoria_id = :categoria';
            $params[':categoria'] = $categoriaId;
        }
        if ($q !== '') {
            $sql .= ' AND (t.titulo LIKE :q_titulo OR t.descripcion LIKE :q_descripcion)';
            $patron = '%' . addcslashes($q, '%_\\') . '%';
            $params[':q_titulo']      = $patron;
            $params[':q_descripcion'] = $patron;
        }
        $sql .= ' ORDER BY t.fecha_obra DESC, t.id DESC';

        $stmt = Database::conexion()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
