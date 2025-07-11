#!/bin/bash
# Definir o usuário do servidor web. Comente o que não for correto.
# Se você não tiver certeza, 'www-data' é comum para Nginx/Apache no Ubuntu/Debian.
# 'apache' é comum para CentOS/RHEL.
HTTPDUSER=www-data
# HTTPDUSER=apache

# Navegar para o diretório raiz do aplicativo Laravel
# Certifique-se de que este script está no diretório raiz ao executar
APP_PATH=$(pwd)

# 1. Definir o proprietário dos arquivos
# O ideal é que seu usuário seja o proprietário e o usuário do servidor web seja o grupo.
# Se você usa SSH com um usuário chamado 'meuusuario', substitua-o abaixo.
# sudo chown -R $USER:$HTTPDUSER $APP_PATH
# Como alternativa, para começar, vamos garantir que o servidor web possa escrever.
sudo chown -R $HTTPDUSER:$HTTPDUSER $APP_PATH/storage $APP_PATH/bootstrap/cache

# 2. Definir permissões de diretório e arquivo
echo "Definindo permissões..."
sudo find $APP_PATH/storage -type d -exec chmod 775 {} \;
sudo find $APP_PATH/bootstrap/cache -type d -exec chmod 775 {} \;
sudo find $APP_PATH/storage -type f -exec chmod 664 {} \;
sudo find $APP_PATH/bootstrap/cache -type f -exec chmod 664 {} \;

# 3. Limpar cache de configuração para garantir que as novas configurações sejam carregadas
php artisan config:clear
php artisan cache:clear

echo "Permissões e cache do Laravel foram ajustados."
echo "Por favor, tente enviar um PDF novamente."
