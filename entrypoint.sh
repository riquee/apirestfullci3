#!/bin/bash
set -e

echo "Aguardando o banco de dados..."
/usr/local/bin/wait-for-it.sh db:3306 -t 30

echo "Banco de dados disponível!"

echo "Executando migrations..."
php index.php migrate

echo "Iniciando o servidor..."
exec apache2-foreground
