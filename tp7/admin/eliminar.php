<?php
// DELETE: baja de un trabajo (solo por POST con token CSRF)
require_once '../includes/funciones.php';
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

if (!csrf_valido()) {
    flash('error', 'No se pudo verificar la solicitud. Intentá de nuevo.');
    redirigir('index.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    flash('error', 'El identificador del trabajo no es válido.');
    redirigir('index.php');
}

try {
    $stmt = $pdo->prepare('DELETE FROM trabajos WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        flash('exito', 'El trabajo se eliminó correctamente.');
    } else {
        flash('error', 'El trabajo no existe o ya fue eliminado.');
    }
} catch (PDOException $ex) {
    error_log('Error al eliminar trabajo: ' . $ex->getMessage());
    flash('error', 'No se pudo eliminar el trabajo.');
}

redirigir('index.php');
