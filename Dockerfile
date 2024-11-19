# Use uma imagem base para o PHP
FROM php:7.4-apache

# Instale as dependências do PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli

# Instalar o MySQL (imagem oficial)
FROM mysql:5.7

# Defina a senha de root, nome do banco, usuário e senha do banco
ENV MYSQL_ROOT_PASSWORD=rootpassword
ENV MYSQL_DATABASE=nome_do_banco
ENV MYSQL_USER=usuario
ENV MYSQL_PASSWORD=senha

# Exponha as portas
EXPOSE 3306

# Volumes para persistência de dados
VOLUME /var/lib/mysql
