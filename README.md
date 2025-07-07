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
git clone https://github.com/seu-usuario/mdlib-2.0.git
cd mdlib-2.0
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

## Licença
Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## Autor
Desenvolvido com ❤️ para tornar o conteúdo mais acessível em Libras.

