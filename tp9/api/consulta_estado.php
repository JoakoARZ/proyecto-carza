<?php
// POST api/consulta_estado.php  con cuerpo JSON {"id": 5, "estado_id": 2}
// Solo administradores. Requiere la cabecera X-CSRF-Token.
// 200 OK · 400 datos inválidos · 401 sin sesión · 403 sin permisos · 404 no existe · 405 método · 500 error
require_once __DIR__ . '/_comun.php';

exigir_metodo('POST');
exigir_admin();
exigir_csrf_cabecera();

$datos     = leer_json();
$id        = filter_var($datos['id'] ?? null, FILTER_VALIDATE_INT);
$estado_id = filter_var($datos['estado_id'] ?? null, FILTER_VALIDATE_INT);

if (!$id || $id < 1 || !$estado_id || $estado_id < 1) {
    responder_error(400, 'Los campos "id" y "estado_id" son obligatorios y deben ser números.');
}

try {
    $stmt = $pdo->prepare('SELECT nombre FROM estados_consulta WHERE id = :id');
    $stmt->execute([':id' => $estado_id]);
    $estado = $stmt->fetch();
    if (!$estado) {
        responder_error(404, 'El estado indicado no existe.');
    }

    $stmt = $pdo->prepare('SELECT id FROM consultas WHERE id = :id');
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        responder_error(404, 'La consulta indicada no existe.');
    }

    $stmt = $pdo->prepare('UPDATE consultas SET estado_id = :estado_id WHERE id = :id');
    $stmt->execute([':estado_id' => $estado_id, ':id' => $id]);

    responder(200, [
        'status'  => 'success',
        'message' => 'El estado de la consulta se actualizó a «' . $estado['nombre'] . '».',
        'data'    => ['id' => $id, 'estado_id' => $estado_id, 'estado' => $estado['nombre']],
    ]);
} catch (PDOException $ex) {
    error_log('api/consulta_estado: ' . $ex->getMessage());
    responder_error(500, 'Error al actualizar la base de datos.');
}
