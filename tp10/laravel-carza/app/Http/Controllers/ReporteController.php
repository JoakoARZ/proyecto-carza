<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Consulta;
use App\Models\EstadoConsulta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ReporteController extends Controller
{
    /** Vista Blade con el resumen de consultas (GET /reportes?estado=1). */
    public function index(Request $request): View
    {
        $estadoId = $request->integer('estado');   // 0 si falta o no es un número

        return view('reportes.index', [
            'estadoId'  => $estadoId,
            'total'     => Consulta::count(),
            'porEstado' => EstadoConsulta::withCount('consultas')->orderBy('id')->get(),
            'porRubro'  => Categoria::withCount('consultas')->orderByDesc('consultas_count')->get(),
            'consultas' => $this->consultasFiltradas($estadoId),
        ]);
    }

    /** API REST simple en JSON (GET /api/reportes/consultas?estado=1). */
    public function api(Request $request): JsonResponse
    {
        $validador = Validator::make($request->query(), [
            'estado' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($validador->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El parámetro "estado" debe ser un número entero positivo.',
            ], 400);
        }

        $estadoId = (int) $request->query('estado', 0);

        if ($estadoId > 0 && ! EstadoConsulta::whereKey($estadoId)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El estado indicado no existe.',
            ], 404);
        }

        $consultas = $this->consultasFiltradas($estadoId);

        return response()->json([
            'status'     => 'success',
            'total'      => $consultas->count(),
            'por_estado' => EstadoConsulta::withCount('consultas')->orderBy('id')->get()
                ->map(fn ($estado) => ['estado' => $estado->nombre, 'cantidad' => $estado->consultas_count]),
            'data'       => $consultas->map(fn ($c) => [
                'id'      => $c->id,
                'cliente' => $c->nombre,
                'estado'  => $c->estado->nombre,
                'rubros'  => $c->categorias->pluck('nombre'),
                'fecha'   => optional($c->created_at)->toDateTimeString(),
            ])->values(),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /** Consultas con su estado y rubros (Eloquent con carga anticipada), filtradas por estado si corresponde. */
    private function consultasFiltradas(int $estadoId): Collection
    {
        return Consulta::with(['estado', 'categorias'])
            ->when($estadoId > 0, fn ($consulta) => $consulta->where('estado_id', $estadoId))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }
}
