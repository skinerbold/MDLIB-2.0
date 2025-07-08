# MDLIB 2.0

## Descrição
MDLIB 2.0 é uma aplicação web desenvolvida em Laravel para transformar conteúdo em Libras com inovação e propósito. O sistema permite upload de arquivos PDF, criação de glossários e geração de conteúdo traduzido para Libras com avatar.

## Funcionalidades
- 📄 Upload e visualização de arquivos PDF
- 📝 Criação e edição de glossários
- 🤟 Tradução de conteúdo para Libras
- 👤 Sistema de avatar para apresentação
- 📊 Dashboard com estatísticas e controles
- 🔐 Sistema de autenticação de usuários
- 📁 Gerenciamento de arquivos processados

## Tecnologias Utilizadas
- **Backend:** Laravel 11
- **Frontend:** Bootstrap, HTML5, CSS3, JavaScript
- **Database:** SQLite
- **Icons:** Font Awesome
- **Build Tools:** Vite

## Requisitos do Sistema
- PHP >= 8.2
- Composer
- Node.js >= 18
- SQLite

## Instalação

### 1. Clone o repositório
```bash
git clone https://github.com/skinerbold/MDLIB-2.0.git
cd MDLIB-2.0
```

### 2. Instale as dependências do PHP
```bash
composer install
```

### 3. Instale as dependências do Node.js
```bash
npm install
```

### 4. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure o banco de dados
```bash
php artisan migrate
php artisan db:seed
```

### 6. Compile os assets
```bash
npm run build
```

### 7. Inicie o servidor
```bash
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`

## Estrutura do Projeto
```
mdlib-app/
├── app/
│   ├── Http/Controllers/     # Controladores da aplicação
│   ├── Models/              # Modelos Eloquent
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Migrações do banco
│   └── seeders/            # Seeders
├── resources/
│   ├── views/              # Templates Blade
│   ├── css/                # Estilos CSS
│   └── js/                 # Scripts JavaScript
├── routes/                 # Definições de rotas
└── public/                # Assets públicos
```

## Páginas Principais
- **Início:** Dashboard principal com upload de PDFs e criação de glossários
- **Arquivos:** Gerenciamento de arquivos enviados
- **Processados:** Visualização de arquivos já processados
- **Guia:** Documentação e ajuda do sistema
- **Auditoria:** Logs e histórico de ações
- **Equipe:** Gerenciamento de usuários
- **Painel ADM:** Controles administrativos

## Contribuição
1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## Deploy

### Deploy no Render

Este projeto está configurado para deploy automático no [Render](https://render.com). Para fazer o deploy:

1. **Conecte seu repositório GitHub ao Render**
2. **Selecione "New Web Service"**
3. **Configure as opções:**
   - **Build Command:** `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
   - **Pre-Deploy Command:** `php artisan migrate --force`
   - **Environment:** Production

### Comandos Alternativos (se o acima não funcionar):
- **Build Command:** `./render-build.sh`
- **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

### Variáveis de Ambiente Necessárias

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=(clique em Generate no Render)
DB_CONNECTION=sqlite
DB_DATABASE=/opt/render/project/src/database/database.sqlite
LOG_CHANNEL=errorlog
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

