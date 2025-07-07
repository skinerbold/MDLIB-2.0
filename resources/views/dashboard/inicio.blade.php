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
        <!-- Title Section -->
        <div class="title-section">
            <h1>Transforme seu conteúdo em Libras com inovação e propósito</h1>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Content Boxes -->
            <div class="row main-boxes-row">
                <!-- PDF Original Section -->
                <div class="col-md-4">
                    <div class="main-box">
                        <h3 class="box-title">PDF<br>ORIGINAL</h3>
                        <div class="expand-icon">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <div class="box-content">
                            <div id="pdf-viewer" class="viewer-area">
                                <i class="fas fa-file-pdf fa-4x"></i>
                                <p class="mt-3">Selecione um arquivo PDF</p>
                            </div>
                        </div>
                        
                        <!-- Botões do PDF Original -->
                        <div class="box-buttons">
                            <button class="btn btn-custom" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-folder-open me-1"></i>Arquivos
                            </button>
                            <button class="btn btn-custom" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-upload me-1"></i>Upload
                            </button>
                            <input type="file" id="fileInput" accept=".pdf" style="display: none;" onchange="handleFileUpload(this)">
                        </div>
                    </div>
                </div>

                <!-- Glossário Section -->
                <div class="col-md-4">
                    <div class="main-box">
                        <h3 class="box-title">GLOSSÁRIO</h3>
                        <div class="expand-icon">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <div class="box-content">
                            <div class="viewer-area">
                                <textarea id="glosa-textarea" class="form-control h-100" placeholder="Digite o glossário aqui..." style="border: none; resize: none; background: transparent; outline: none;"></textarea>
                            </div>
                        </div>
                        
                        <!-- Botões do Glossário -->
                        <div class="box-buttons">
                            <button class="btn btn-custom" onclick="editGlosa()">
                                <i class="fas fa-edit me-1"></i>Editar
                            </button>
                            <button class="btn btn-custom" onclick="saveGlosa()">
                                <i class="fas fa-save me-1"></i>Salvar
                            </button>
                            <button class="btn btn-custom" onclick="translateGlosa()">
                                <i class="fas fa-language me-1"></i>Traduzir
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Avatar Section -->
                <div class="col-md-4">
                    <div class="main-box">
                        <h3 class="box-title">AVATAR</h3>
                        <div class="expand-icon">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <div class="box-content">
                            <div class="viewer-area">
                                <!-- Avatar Section -->
                                <div class="mini-section">
                                    <h6>AVATAR</h6>
                                    <small class="text-muted">Área do avatar</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botões do Avatar -->
                        <div class="box-buttons">
                            <button class="btn btn-custom" onclick="cancelOperation()">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </button>
                            <button class="btn btn-custom" onclick="downloadData()">
                                <i class="fas fa-download me-1"></i>Baixar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Table -->
            <div class="file-table">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME DO ARQUIVO</th>
                            <th>TIPO</th>
                            <th>GLOSA</th>
                            <th>TRADUÇÃO</th>
                            <th>USUÁRIO</th>
                            <th>DATA DO REGISTRO</th>
                            <th>DATA DA ATUALIZAÇÃO</th>
                            <th>EXCLUIR</th>
                            <th>BAIXAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>documento_exemplo.pdf</td>
                            <td>PDF</td>
                            <td>Não</td>
                            <td>Não</td>
                            <td>Usuário Demo</td>
                            <td>07/07/2025 14:30</td>
                            <td>07/07/2025 14:30</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" onclick="alert('Arquivo excluído (demo)')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" onclick="alert('Download iniciado (demo)')">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <em>Esta é uma demonstração. Em breve você poderá gerenciar arquivos reais.</em>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Funcionalidades interativas
    document.addEventListener('DOMContentLoaded', function() {
        console.log('MDLIB 2.0 Dashboard carregado!');
    });

    // Função para lidar com upload de arquivo
    function handleFileUpload(input) {
        const file = input.files[0];
        if (file && file.type === 'application/pdf') {
            const pdfViewer = document.getElementById('pdf-viewer');
            pdfViewer.innerHTML = `
                <i class="fas fa-file-pdf fa-4x text-success"></i>
                <p class="mt-3 text-success">${file.name}</p>
                <small class="text-muted">Arquivo carregado com sucesso!</small>
            `;
            
            // Simulação de processamento
            setTimeout(() => {
                alert('PDF carregado: ' + file.name + '\n\nFuncionalidade de visualização será implementada em breve.');
            }, 500);
        } else {
            alert('Por favor, selecione um arquivo PDF válido.');
        }
    }

    // Funções da Glosa
    function editGlosa() {
        const textarea = document.getElementById('glosa-textarea');
        textarea.disabled = false;
        textarea.focus();
        alert('Modo de edição ativado!');
    }

    function saveGlosa() {
        const textarea = document.getElementById('glosa-textarea');
        const content = textarea.value.trim();
        
        if (content) {
            alert('Glosa salva com sucesso!\n\nConteúdo: ' + content.substring(0, 50) + (content.length > 50 ? '...' : ''));
        } else {
            alert('Digite algum conteúdo na glosa antes de salvar.');
        }
    }

    function translateGlosa() {
        const textarea = document.getElementById('glosa-textarea');
        const content = textarea.value.trim();
        
        if (content) {
            // Simulação de tradução
            alert('Iniciando tradução...\n\nFuncionalidade de tradução será implementada em breve.');
        } else {
            alert('Digite algum conteúdo na glosa antes de traduzir.');
        }
    }

    // Funções do Avatar/Legendas/Imagens
    function cancelOperation() {
        if (confirm('Tem certeza que deseja cancelar a operação atual?')) {
            // Reset dos campos
            document.getElementById('glosa-textarea').value = '';
            document.getElementById('pdf-viewer').innerHTML = `
                <i class="fas fa-file-pdf fa-4x"></i>
                <p class="mt-3">Selecione um arquivo PDF</p>
            `;
            alert('Operação cancelada!');
        }
    }

    function downloadData() {
        const textarea = document.getElementById('glosa-textarea');
        const content = textarea.value.trim();
        
        if (content) {
            // Simulação de download
            alert('Preparando download dos dados...\n\nFuncionalidade de download será implementada em breve.');
        } else {
            alert('Não há dados para baixar. Adicione conteúdo primeiro.');
        }
    }

    // Funcionalidade de expansão dos boxes (futura implementação)
    document.querySelectorAll('.expand-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            alert('Funcionalidade de expansão será implementada em breve!');
        });
    });
</script>
@endsection
