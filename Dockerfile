FROM php:7.2-apache

ENV CELY_DEST_DIR=/var/www/html

ARG CELY_TEMPORARY_COMPOSER_FILE=$CELY_DEST_DIR/site/php/composer.phar

RUN DEBIAN_FRONTEND=noninteractive apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y git libxml2-dev && \
    docker-php-ext-install soap && \
    a2enmod rewrite && \
    mkdir -p $(dirname "$CELY_TEMPORARY_COMPOSER_FILE") && \
    curl -sS -o "$CELY_TEMPORARY_COMPOSER_FILE" http://getcomposer.org/composer.phar

COPY ./ "$CELY_DEST_DIR"

RUN cd $(dirname "$CELY_TEMPORARY_COMPOSER_FILE") && \
    php composer.phar install --no-dev --no-progress --optimize-autoloader && \
    DEBIAN_FRONTEND=noninteractive apt-get purge -y git libxml2-dev && \
    DEBIAN_FRONTEND=noninteractive apt-get autoremove -y && \
    DEBIAN_FRONTEND=noninteractive apt-get clean -y
