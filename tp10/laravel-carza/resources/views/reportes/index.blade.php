@extends('layouts.app')

@section('titulo', 'Reporte de consultas')

@section('contenido')
    <section class="page-banner">
        <div class="container">
            <p class="eyebrow eyebrow-light">Reportes</p>
            <h1 class="display-5 fw-bold text-white">Consultas de presupuesto</h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-2">
                    <a class="card-servicio d-block h-100 text-decoration-none text-reset" href="{{ route('reportes.index') }}">
                        <p class="eyebrow mb-1">Total</p>
                        <p class="display-6 fw-bold mb-0">{{ $total }}</p>
                    </a>
                </div>
                @foreach ($porEstado as $estado)
                    <div class="col-6 col-lg-2">
                        <a class="card-servicio d-block h-100 text-decoration-none text-reset {{ $estadoId === $estado->id ? 'border border-2 border-dark' : '' }}"
                           href="{{ route('reportes.index', ['estado' => $estado->id]) }}">
                            <p class="eyebrow mb-1">{{ $estado->nombre }}</p>
                            <p class="display-6 fw-bold mb-0">{{ $estado->consultas_count }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="row g-5">
                <div class="col-lg-8">
                    <h2 class="h4 fw-bold">
                        Consultas
                        @if ($estadoId > 0)
                            <small class="text-muted-ac fs-6">(filtradas por estado) <a class="link-acero" href="{{ route('reportes.index') }}">Quitar filtro</a></small>
                        @endif
                    </h2>

                    @if ($consultas->isEmpty())
                        <div class="alert alert-info mt-3">No hay consultas para mostrar.</div>
                    @else
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Rubros</th>
                                        <th scope="col">Mensaje</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($consultas as $consulta)
                                        <tr>
                                            <td class="text-nowrap">{{ optional($consulta->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $consulta->nombre }}</td>
                                            <td>{{ $consulta->categorias->pluck('nombre')->join(', ') ?: '—' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($consulta->mensaje, 80) }}</td>
                                            <td><span class="badge text-bg-warning">{{ $consulta->estado->nombre }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <h2 class="h4 fw-bold">Consultas por rubro</h2>
                    <ul class="list-group mt-3">
                        @foreach ($porRubro as $rubro)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $rubro->nombre }}
                                <span class="badge text-bg-secondary rounded-pill">{{ $rubro->consultas_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
