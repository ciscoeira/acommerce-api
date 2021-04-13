FROM php:7.4-fpm-alpine

RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add git \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /tmp/* \
    && apk del .phpize-deps

RUN curl --show-error --silent https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer
#    \
#    && composer install -v

WORKDIR /var/www/html