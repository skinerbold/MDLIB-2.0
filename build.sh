#!/usr/bin/env bash
# Build script para deploy no Render
set -o errexit

echo "🚀 Iniciando build do MDLIB 2.0..."

# Instalar Composer se não estiver disponível
if ! command -v composer &> /dev/null; then
    echo "📦 Instalando Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# Instalar dependências PHP
echo "📦 Instalando dependências PHP..."
composer install --optimize-autoloader --no-dev

# Gerar chave da aplicação se não existir
echo "🔑 Configurando chave da aplicação..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --show
fi

# Cache de configurações
echo "⚡ Otimizando configurações..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Executar migrações
echo "🗃️ Executando migrações do banco..."
php artisan migrate --force

# Instalar dependências Node.js
echo "📦 Instalando dependências Node.js..."
npm ci

# Build dos assets
echo "🎨 Compilando assets..."
npm run build

echo "✅ Build concluído com sucesso!"
