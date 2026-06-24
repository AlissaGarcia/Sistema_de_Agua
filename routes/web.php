<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsumidorController;
use App\Http\Controllers\LeituraController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\LeituristaController;
use App\Http\Middleware\RoleMiddleware;

// Rotas públicas (Login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Redirecionar raiz para login
Route::get('/', function () {
    return redirect('/login');
});

// Rotas autenticadas
Route::middleware(['auth'])->group(function () {
    // Leituras disponíveis para gestor e leiturista
    Route::middleware([RoleMiddleware::class . ':gestor|leiturista'])->group(function () {
        Route::resource('leituras', LeituraController::class, [
            'only' => ['index', 'create', 'store', 'show', 'destroy']
        ]);
    });

    // Rotas exclusivas do gestor
    Route::middleware([RoleMiddleware::class . ':gestor'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('consumidores', ConsumidorController::class);
        Route::resource('faturas', FaturaController::class, [
            'only' => ['index', 'show', 'destroy']
        ]);
        Route::patch('/faturas/{id}/marcar-pago', [FaturaController::class, 'marcarPago'])->name('faturas.marcar-pago');
        Route::get('/faturas/{id}/pdf', [FaturaController::class, 'gerarPDF'])->name('faturas.pdf');
        Route::post('/faturas/{id}/email', [FaturaController::class, 'enviarEmail'])->name('faturas.email');
        Route::get('/configuracao', [ConfiguracaoController::class, 'index'])->name('configuracao.index');
        Route::patch('/configuracao', [ConfiguracaoController::class, 'update'])->name('configuracao.update');
    });

    // Painel exclusivo do leiturista
    Route::middleware([RoleMiddleware::class . ':leiturista'])->group(function () {
        Route::get('/leiturista', [LeituristaController::class, 'index'])->name('leiturista.dashboard');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
