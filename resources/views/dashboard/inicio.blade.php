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
                    <div class="main-box avatar-box">
                        <h3 class="box-title">AVATAR</h3>
                        <div class="expand-icon" onclick="window.open('/avatar', '_blank')">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <div class="box-content">
                            <div class="viewer-area" id="scene-container" style="position: relative;">
                                {{-- O JavaScript do avatar irá renderizar a cena 3D aqui --}}
                            </div>
                        </div>
                        
                        <!-- Botões do Avatar -->
                        <div class="box-buttons">
                            <button class="btn btn-custom" onclick="playAnimation('talk')">
                                <i class="fas fa-comment me-1"></i>Falar
                            </button>
                            <button class="btn btn-custom" onclick="playAnimation('wave')">
                                <i class="fas fa-hand-paper me-1"></i>Acenar
                            </button>
                            <button class="btn btn-custom" onclick="resetAvatar()">
                                <i class="fas fa-redo me-1"></i>Reset
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
        
        // Adicionar animação de bounce ao CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-10px); }
                60% { transform: translateY(-5px); }
            }
        `;
        document.head.appendChild(style);
    });

    // Avatar simples para preview
    let avatarPreviewActive = false;
    
    function toggleAvatarPreview() {
        const container = document.getElementById('avatar-container');
        if (!container) return;
        
        avatarPreviewActive = !avatarPreviewActive;
        
        if (avatarPreviewActive) {
            container.innerHTML = `
                <div class="avatar-preview-active" style="text-align: center;">
                    <div class="avatar-icon" style="animation: bounce 2s infinite;">
                        <i class="fas fa-robot fa-3x text-primary"></i>
                    </div>
                    <p class="mt-2 text-primary"><strong>Ana - Avatar ativo</strong></p>
                    <small class="text-muted">Clique em "Abrir Avatar" para ver em 3D</small>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="avatar-preview">
                    <i class="fas fa-user-circle fa-4x text-muted"></i>
                    <p class="mt-3 text-muted">Clique para ver o Avatar 3D</p>
                </div>
            `;
        }
    }

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

    // Funções do Avatar 3D
    function playAvatarAnimation(animationName) {
        if (avatarRenderer) {
            avatarRenderer.playAnimation(animationName);
            console.log('Reproduzindo animação:', animationName);
        } else {
            console.warn('Avatar não inicializado');
        }
    }

    function stopAvatarAnimation() {
        if (avatarRenderer) {
            avatarRenderer.stopAnimation();
            console.log('Animação parada');
        } else {
            console.warn('Avatar não inicializado');
        }
    }

    function resetAvatarPosition() {
        if (avatarRenderer) {
            avatarRenderer.resetPosition();
            console.log('Posição do avatar resetada');
        } else {
            console.warn('Avatar não inicializado');
        }
    }
</script>

{{-- Estilos específicos para o avatar no box --}}
<style>
    #scene-container {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    #scene-container canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
        border-radius: 8px;
    }

    .status-info {
        position: absolute;
        top: 5px;
        left: 5px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 10px;
        z-index: 10;
    }

    .loading-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #666;
        font-size: 12px;
    }

    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* CSS específico para o box do Avatar - orientação vertical */
    .avatar-box {
        height: 520px !important; /* Mesma altura dos outros boxes - aumentada */
    }

    .avatar-box .viewer-area {
        width: 100%; /* Usa toda a largura disponível */
        height: 100%;
        aspect-ratio: auto; /* Remove aspect-ratio fixo */
        padding: 0 !important; /* Remove padding para maximizar área útil */
        border: none !important; /* Remove borda para mais espaço */
    }

    .avatar-box #scene-container {
        width: 100%;
        height: 100%;
        min-height: 300px; /* Altura mínima ajustada */
        border-radius: 8px; /* Mantém o border-radius */
        overflow: hidden; /* Garante que o conteúdo 3D respeite o border-radius */
    }

    /* Garantir que os botões do avatar fiquem alinhados */
    .avatar-box .box-buttons {
        flex-shrink: 0; /* Impede que os botões sejam comprimidos */
        margin-top: auto; /* Empurra os botões para o final do container */
    }
</style>
@endsection
