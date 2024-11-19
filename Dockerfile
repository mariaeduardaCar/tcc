# Usando a imagem oficial do PHP com Apache
FROM php:8.0-apache

# Copiar todos os arquivos do projeto para o diretório do Apache
COPY . /var/www/html/

# Expor a porta 80 (padrão para servidores web)
EXPOSE 80
