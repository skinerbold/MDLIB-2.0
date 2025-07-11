<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AvatarController;

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

// Rotas para o Avatar
Route::get('/avatar', [AvatarController::class, 'show'])->name('avatar.show');
Route::post('/avatar/animate', [AvatarController::class, 'animate'])->name('avatar.animate');
Route::post('/avatar/stop', [AvatarController::class, 'stop'])->name('avatar.stop');

// Rotas para arquivos 
Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
Route::get('/files/{id}/download', [FileController::class, 'download'])->name('files.download');
Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');
Route::put('/files/{id}', [FileController::class, 'update'])->name('files.update');
Route::get('/files/list', [FileController::class, 'list'])->name('files.list');
Route::post('/files/{id}/extract-text', [FileController::class, 'extractText'])->name('files.extract-text');

// Rota de teste
Route::post('/test-upload', function(\Illuminate\Http\Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Teste de conexão OK!',
        'extraction' => [
            'success' => true,
            'text' => 'Este é um texto de teste para verificar se a comunicação está funcionando.',
            'word_count' => 15,
            'char_count' => 70
        ]
    ]);
})->name('test-upload');

// Rota de teste GET para verificar o serviço
Route::get('/test-service', function() {
    try {
        $service = new \App\Services\PdfTextExtractorService();
        return response()->json([
            'success' => true,
            'message' => 'Serviço PdfTextExtractorService está funcionando!',
            'service' => get_class($service)
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test-service');
