#!/usr/bin/env bash
# Build script simplificado para Render
set -e

echo "🚀 Build MDLIB 2.0..."

# Instalar dependências PHP
composer install --no-dev --optimize-autoloader

# Instalar dependências Node.js  
npm ci

# Build assets
npm run build

echo "✅ Build concluído!"
