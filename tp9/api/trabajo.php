<?php
// GET api/trabajo.php?id=3  -> detalle de un trabajo publicado
// 200 OK · 400 id inválido · 404 no existe · 405 método · 500 error de base de datos
require_once __DIR__ . '/_comun.php';

exigir_metodo('GET');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    responder_error(400, 'El identificador del trabajo no es válido.');
}

try {
    $stmt = $pdo->prepare(
        'SELECT t.id, t.titulo, t.descripcion, t.imagen, t.fecha_obra,
                c.nombre AS categoria, l.nombre AS localidad
         FROM trabajos t
         INNER JOIN categorias c ON c.id = t.categoria_id
         LEFT JOIN localidades l ON l.id = t.localidad_id
         WHERE t.id = :id AND t.publicado = 1'
    );
    $stmt->execute([':id' => $id]);
    $trabajo = $stmt->fetch();

    if (!$trabajo) {
        responder_error(404, 'El trabajo solicitado no existe.');
    }

    responder(200, ['status' => 'success', 'data' => $trabajo]);
} catch (PDOException $ex) {
    error_log('api/trabajo: ' . $ex->getMessage());
    responder_error(500, 'Error al consultar la base de datos.');
}
