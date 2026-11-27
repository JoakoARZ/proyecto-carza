<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Tabla estados_consulta (Pendiente, En contacto, Presupuestada...). */
class EstadoConsulta extends Model
{
    protected $table = 'estados_consulta';

    public $timestamps = false;

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'estado_id');
    }
}
