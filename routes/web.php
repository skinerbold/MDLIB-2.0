<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Auth::routes();

// Rotas temporárias sem autenticação para teste
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/inicio', [DashboardController::class, 'inicio'])->name('inicio');
Route::get('/arquivos', [DashboardController::class, 'arquivos'])->name('arquivos');
Route::get('/processados', [DashboardController::class, 'processados'])->name('processados');
Route::get('/guia', [DashboardController::class, 'guia'])->name('guia');
Route::get('/auditoria', [DashboardController::class, 'auditoria'])->name('auditoria');
Route::get('/equipe', [DashboardController::class, 'equipe'])->name('equipe');
Route::get('/painel-adm', [DashboardController::class, 'painelAdm'])->name('painel-adm');
Route::get('/meus-dados', [DashboardController::class, 'meusDados'])->name('meus-dados');

// Rotas para arquivos (temporariamente desabilitadas)
// Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
// Route::get('/files/{id}/download', [FileController::class, 'download'])->name('files.download');
// Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');
// Route::put('/files/{id}', [FileController::class, 'update'])->name('files.update');
