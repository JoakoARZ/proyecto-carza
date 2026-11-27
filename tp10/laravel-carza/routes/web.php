<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

// Módulo de reportes de consultas (TP10 – Parte B)
Route::get('/', fn () => redirect()->route('reportes.index'));

Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('/api/reportes/consultas', [ReporteController::class, 'api'])->name('reportes.api');
