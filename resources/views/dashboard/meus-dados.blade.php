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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Meus Dados</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('meus-dados') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">Nome</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name', 'Usuário Demo') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', 'demo@mdlib.com') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">Nova Senha</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" placeholder="Deixe em branco para manter a senha atual">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmar Senha</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" 
                                               name="password_confirmation" placeholder="Confirme a nova senha">
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Atualizar Dados
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
