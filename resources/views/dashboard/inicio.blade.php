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
                        <h3 class="box-title">PDF ORIGINAL</h3>
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
                                <textarea id="glosa-textarea" class="form-control h-100" placeholder="Digite o glossário..." style="border: none; resize: none; background: transparent; outline: none;"></textarea>
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
                                <i class="fas fa-user-circle fa-4x text-muted"></i>
                                <p class="mt-3 text-muted">Área do Avatar</p>
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
                            <th>Arquivo</th>
                            <th>Tipo</th>
                            <th>Glossário</th>
                            <th>Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>exemplo.pdf</td>
                            <td>PDF</td>
                            <td>Não</td>
                            <td>Pedro M.</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger me-1" title="Excluir arquivo">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" title="Baixar arquivo">
                                    <i class="fas fa-download"></i>
                                </button>
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
        if (!file) return;

        if (file.type !== 'application/pdf') {
            alert('Por favor, selecione apenas arquivos PDF.');
            return;
        }

        console.log('Iniciando upload:', file.name);

        // Mostrar indicador de carregamento
        const pdfViewer = document.getElementById('pdf-viewer');
        pdfViewer.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2">Processando PDF...</p>
                <small class="text-muted">Extraindo texto do arquivo</small>
            </div>
        `;

        // Verificar se o token CSRF existe
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('Token CSRF não encontrado');
            showUploadError('Erro de segurança: Token CSRF não encontrado');
            return;
        }

        // Preparar dados para upload
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', csrfToken.getAttribute('content'));

        console.log('Fazendo upload para:', '/files/upload'); // Upload real
        console.log('Token CSRF:', csrfToken.getAttribute('content'));

        // Fazer upload
        fetch('/files/upload', { // Usando rota real de upload
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Resposta recebida:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (data.success) {
                // Atualizar visualização do PDF
                updatePdfViewer(data);
                
                // Atualizar glossário se texto foi extraído
                if (data.extraction.success) {
                    updateGlossary(data.extraction.text);
                    showExtractionSuccess(data);
                } else {
                    showExtractionError(data);
                }
            } else {
                showUploadError(data.message || 'Erro desconhecido no servidor');
            }
        })
        .catch(error => {
            console.error('Erro detalhado:', error);
            showUploadError(`Erro de conexão: ${error.message}`);
        });
    }

    // Atualizar visualização do PDF
    function updatePdfViewer(data) {
        const pdfViewer = document.getElementById('pdf-viewer');
        pdfViewer.innerHTML = `
            <div class="text-center">
                <i class="fas fa-file-pdf fa-4x text-success"></i>
                <p class="mt-3 text-success"><strong>${data.filename}</strong></p>
                <small class="text-muted">Arquivo carregado com sucesso!</small>
            </div>
        `;
    }

    // Atualizar glossário com texto extraído
    function updateGlossary(extractedText) {
        const textarea = document.getElementById('glosa-textarea');
        if (textarea && extractedText) {
            textarea.value = extractedText;
            textarea.style.background = '#f0fff0'; // Fundo verde claro para indicar sucesso
            
            // Remover destaque após 3 segundos
            setTimeout(() => {
                textarea.style.background = 'transparent';
            }, 3000);
        }
    }

    // Mostrar sucesso na extração
    function showExtractionSuccess(data) {
        const message = `
            ✅ Texto extraído com sucesso!
            
            📄 Arquivo: ${data.filename}
            📝 Palavras: ${data.word_count || 'N/A'}
            📊 Caracteres: ${data.char_count || 'N/A'}
            
            O texto foi automaticamente adicionado ao Glossário.
        `;
        
        // setTimeout(() => alert(message), 500); // Popup removido - notificação desabilitada
    }

    // Mostrar erro na extração
    function showExtractionError(data) {
        let message = `
            ⚠️ Upload realizado, mas houve problema na extração de texto:
            
            📄 Arquivo: ${data.filename}
            ❌ Erro: ${data.extraction_message}
        `;

        if (data.extraction_status === 'failed') {
            message += `
            
            💡 Possíveis soluções:
            • Verificar se o PDF não está protegido
            • Tentar com um PDF que contenha texto selecionável
            • Considerar usar OCR para PDFs digitalizados
            `;
        }
        
        // setTimeout(() => alert(message), 500); // Popup removido - notificação desabilitada
    }

    // Mostrar erro no upload
    function showUploadError(message) {
        const pdfViewer = document.getElementById('pdf-viewer');
        pdfViewer.innerHTML = `
            <div class="text-center text-danger">
                <i class="fas fa-exclamation-triangle fa-4x"></i>
                <p class="mt-3">Erro no upload</p>
                <small>${message}</small>
            </div>
        `;
        
        setTimeout(() => {
            pdfViewer.innerHTML = `
                <i class="fas fa-file-pdf fa-4x"></i>
                <p class="mt-3">Selecione um arquivo PDF</p>
            `;
        }, 5000);
    }

    // Funções do Glossário
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
            alert('Glossário salvo com sucesso!\n\nConteúdo: ' + content.substring(0, 50) + (content.length > 50 ? '...' : ''));
        } else {
            alert('Digite algum conteúdo no glossário antes de salvar.');
        }
    }

    function translateGlosa() {
        const textarea = document.getElementById('glosa-textarea');
        const content = textarea.value.trim();
        
        if (content) {
            // Simulação de tradução
            alert('Iniciando tradução...\n\nFuncionalidade de tradução será implementada em breve.');
        } else {
            alert('Digite algum conteúdo no glossário antes de traduzir.');
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
