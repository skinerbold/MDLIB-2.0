@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" style="border: none;">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">Início</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('arquivos') ? 'active' : '' }}" href="{{ route('arquivos') }}">Arquivos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('processados') ? 'active' : '' }}" href="{{ route('processados') }}">Processados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guia') ? 'active' : '' }}" href="{{ route('guia') }}">Guia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('auditoria') ? 'active' : '' }}" href="{{ route('auditoria') }}">Auditoria</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('equipe') ? 'active' : '' }}" href="{{ route('equipe') }}">Equipe</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('painel-adm') ? 'active' : '' }}" href="{{ route('painel-adm') }}">Painel ADM</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('meus-dados') ? 'active' : '' }}" href="{{ route('meus-dados') }}">Meus Dados</a>
            </li>
        </ul>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <div class="title-section">
            <h1>MDLIB 2.0</h1>
        </div>

        <div class="content-area">
            <div class="text-center">
                <h2>Painel ADM</h2>
                <p>Painel administrativo do sistema</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Esta seção está em desenvolvimento. Em breve você terá acesso a funcionalidades administrativas avançadas.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
