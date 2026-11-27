<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** Tabla categorias (rubros de trabajo). */
class Categoria extends Model
{
    protected $table = 'categorias';

    public $timestamps = false;

    /** Consultas que pidieron este rubro (relación N:M por consulta_categoria). */
    public function consultas(): BelongsToMany
    {
        return $this->belongsToMany(Consulta::class, 'consulta_categoria', 'categoria_id', 'consulta_id');
    }
}
