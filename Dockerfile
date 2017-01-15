FROM php:7.1.0-apache
RUN apt-get update && \
    docker-php-ext-install mysqli