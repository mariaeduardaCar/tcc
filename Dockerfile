FROM php:8.0-apache

# Instalar a extensão MySQLi
RUN docker-php-ext-install mysqli

# Copiar os arquivos do seu projeto para o diretório do Apache
COPY . /var/www/html/

# Expor a porta 80
EXPOSE 80
