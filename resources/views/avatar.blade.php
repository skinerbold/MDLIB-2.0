@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Avatar 3D - Ana</h4>
                </div>
                <div class="card-body">
                    <!-- Container do Avatar 3D -->
                    <div class="avatar-container">
                        <div id="scene-container" class="scene-container">
                            {{-- O JavaScript irá renderizar a cena 3D aqui --}}
                        </div>
                        
                        <!-- Controles do Avatar -->
                        <div class="avatar-controls">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" id="btn-idle">
                                    <i class="fas fa-play"></i> Idle
                                </button>
                                <button type="button" class="btn btn-success" id="btn-talk">
                                    <i class="fas fa-comment"></i> Falar
                                </button>
                                <button type="button" class="btn btn-info" id="btn-wave">
                                    <i class="fas fa-hand-paper"></i> Acenar
                                </button>
                                <button type="button" class="btn btn-warning" id="btn-gesture">
                                    <i class="fas fa-hands"></i> Gesto
                                </button>
                                <button type="button" class="btn btn-danger" id="btn-stop">
                                    <i class="fas fa-stop"></i> Parar
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-container {
        position: relative;
        width: 100%;
        height: 70vh;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .scene-container {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .scene-container canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }

    .avatar-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 15px;
        border-radius: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }

    .btn-group .btn {
        margin: 0 5px;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .loading-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #666;
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .status-info {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        z-index: 10; /* Garante que fique sobre o canvas */
    }
</style>
@endsection

@section('scripts')
{{-- O JavaScript foi movido para resources/js/avatar.js e será compilado pelo Vite --}}
@endsection
