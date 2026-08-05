<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\StreakController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LeccionController;

// 1. Rutas públicas de Autenticación (no requieren token)
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// 2. Rutas protegidas: requieren un token válido de Sanctum (Authorization: Bearer {token})
Route::middleware('auth:sanctum')->group(function () {

    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/me', [AuthController::class, 'me']); // compatibilidad con el frontend actual

    // Ingresos
    Route::get('incomes', [IncomeController::class, 'index']);
    Route::post('incomes', [IncomeController::class, 'store']);
    Route::delete('incomes/{id}', [IncomeController::class, 'destroy']);

    // Gastos
    Route::get('expenses', [ExpenseController::class, 'index']);
    Route::post('expenses', [ExpenseController::class, 'store']);
    Route::delete('expenses/{id}', [ExpenseController::class, 'destroy']);

    // Balance
    Route::get('reports/balance', [ReportController::class, 'getBalance']);

    // Racha
    Route::get('streak', [StreakController::class, 'index']);

    // Análisis psicológico
    Route::get('analysis/psychological', [AnalysisController::class, 'perfilPsicologico']);

    // Finanzas de negocio
    Route::get('businesses', [BusinessController::class, 'index']);
    Route::post('businesses', [BusinessController::class, 'store']);
    Route::put('businesses/{id}', [BusinessController::class, 'update']);
    Route::delete('businesses/{id}', [BusinessController::class, 'destroy']);
    Route::get('businesses/{id}/report', [BusinessController::class, 'reporte']);

    // Educación financiera
    Route::get('lecciones', [LeccionController::class, 'index']);
    Route::get('lecciones/progreso', [LeccionController::class, 'miProgreso']);
    Route::get('lecciones/{id}', [LeccionController::class, 'show']);
    Route::post('lecciones', [LeccionController::class, 'store']);
    Route::post('lecciones/{id}/completar', [LeccionController::class, 'marcarCompletada']);

    // Divisas en tiempo real
    Route::get('currency/rates', [CurrencyController::class, 'rates']);
    Route::get('currency/popular', [CurrencyController::class, 'popular']);
    Route::get('currency/convert', [CurrencyController::class, 'convert']);
});
