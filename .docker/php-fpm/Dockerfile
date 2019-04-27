FROM php:7.3.3-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libpq-dev \
        libxpm-dev \
        libvpx-dev \
        libpcre3-dev \
        libzip-dev \
        git \
        zip \
        unzip \
        wget \
        bash \
        zsh \
    && docker-php-ext-install opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl install xdebug-2.7.0 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
        --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-vpx-dir=/usr/lib/x86_64-linux-gnu/

RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=9034" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN addgroup --gid 1000 docker && \
    adduser --uid 1000 --ingroup docker --home /home/docker --shell /bin/sh --disabled-password --gecos "" docker

RUN echo 'user = 1000' > /usr/local/etc/php-fpm.d/www.conf \
    && echo 'group = 1000' >> /usr/local/etc/php-fpm.d/www.conf \
    && echo 'listen = 127.0.0.1:9000' >> /usr/local/etc/php-fpm.d/www.conf \
    && echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/www.conf \
    && echo 'pm.max_children = 5' >> /usr/local/etc/php-fpm.d/www.conf \
    && echo 'pm.start_servers = 2' >> /usr/local/etc/php-fpm.d/www.conf \
    && echo 'pm.min_spare_servers = 1' >> /usr/local/etc/php-fpm.d/www.conf \
    && echo 'pm.max_spare_servers = 3' >> /usr/local/etc/php-fpm.d/www.conf

USER 1000

WORKDIR /var/www/symshop.loc
