<?php
// GET api/buscar_trabajos.php?q=techo&categoria=2
// 200 OK · 400 parámetros inválidos · 404 categoría inexistente · 405 método · 500 error de base de datos
require_once __DIR__ . '/_comun.php';

exigir_metodo('GET');

$q         = obtener_get('q');
$cat_texto = obtener_get('categoria');

if (mb_strlen($q) > 50) {
    responder_error(400, 'La búsqueda no puede superar los 50 caracteres.');
}

$categoria_id = 0;
if ($cat_texto !== '' && $cat_texto !== '0') {
    $categoria_id = filter_var($cat_texto, FILTER_VALIDATE_INT);
    if ($categoria_id === false || $categoria_id < 1) {
        responder_error(400, 'La categoría indicada no es válida.');
    }
}

try {
    if ($categoria_id !== 0) {
        $stmt = $pdo->prepare('SELECT id FROM categorias WHERE id = :id');
        $stmt->execute([':id' => $categoria_id]);
        if (!$stmt->fetch()) {
            responder_error(404, 'La categoría indicada no existe.');
        }
    }

    $sql = 'SELECT t.id, t.titulo, t.descripcion, t.imagen, c.nombre AS categoria
            FROM trabajos t
            INNER JOIN categorias c ON c.id = t.categoria_id
            WHERE t.publicado = 1';
    $params = [];

    if ($categoria_id !== 0) {
        $sql .= ' AND t.categoria_id = :categoria';
        $params[':categoria'] = $categoria_id;
    }
    if ($q !== '') {
        // Con consultas preparadas reales cada marcador se usa una sola vez
        $sql .= ' AND (t.titulo LIKE :q_titulo OR t.descripcion LIKE :q_descripcion)';
        $patron = '%' . addcslashes($q, '%_\\') . '%';
        $params[':q_titulo']      = $patron;
        $params[':q_descripcion'] = $patron;
    }
    $sql .= ' ORDER BY t.fecha_obra DESC, t.id DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $trabajos = $stmt->fetchAll();

    responder(200, [
        'status' => 'success',
        'total'  => count($trabajos),
        'data'   => $trabajos,
    ]);
} catch (PDOException $ex) {
    error_log('api/buscar_trabajos: ' . $ex->getMessage());
    responder_error(500, 'Error al consultar la base de datos.');
}
