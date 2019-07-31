FROM tunet/php:7.3.7-fpm

RUN pecl install xdebug-2.7.1 && docker-php-ext-enable xdebug

RUN addgroup -g 1000 1000
RUN adduser -u 1000 -G 1000 -D 1000
USER 1000

WORKDIR /var/www/app.loc