<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** Tabla consultas (pedidos de presupuesto). Usa created_at / updated_at. */
class Consulta extends Model
{
    protected $table = 'consultas';

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoConsulta::class, 'estado_id');
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'consulta_categoria', 'consulta_id', 'categoria_id');
    }
}
