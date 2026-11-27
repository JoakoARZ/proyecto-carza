<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Categoria;
use App\Models\Trabajo;

/** Catálogo público de trabajos con búsqueda (GET). */
final class TrabajoController extends Controller
{
    public function index(): void
    {
        $categorias  = Categoria::todas();
        $q           = $this->get('q');
        $categoriaId = (int) (filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0);
        $avisos      = [];

        if (mb_strlen($q) > 50) {
            $avisos[] = 'La búsqueda no puede superar los 50 caracteres; se recortó el texto.';
            $q = mb_substr($q, 0, 50);
        }
        if ($categoriaId !== 0 && !array_key_exists($categoriaId, $categorias)) {
            $avisos[] = 'La categoría elegida no es válida; se muestran todas.';
            $categoriaId = 0;
        }

        $this->vista('trabajos/index', [
            'titulo'      => 'Trabajos realizados',
            'trabajos'    => Trabajo::publicados($q, $categoriaId),
            'categorias'  => $categorias,
            'q'           => $q,
            'categoriaId' => $categoriaId,
            'avisos'      => $avisos,
        ]);
    }
}
