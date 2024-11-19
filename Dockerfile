FROM php:8.0-apache

# Instalar extensões MySQLi e PDO MySQL
RUN docker-php-ext-install mysqli pdo_mysql

# Copiar os arquivos do seu projeto para o diretório do Apache
COPY . /var/www/html/

# Expor a porta 80
EXPOSE 80
