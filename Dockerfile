FROM php:8.3-fpm

LABEL maintainer="Pedro Gaspar"

# Instalar dependências para compilação
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    bash \
    openssl \
    gettext

# Instalar extensões PHP comuns
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar Phalcon 5 via PECL
RUN pecl channel-update pecl.php.net && \
    pecl install phalcon && \
    echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini

# Expor porta FPM (opcional)
EXPOSE 9000

# Pasta do projeto (opcional)
WORKDIR /var/www/html
