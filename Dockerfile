FROM php:8.2-apache

RUN a2enmod rewrite

# Install PHP extensions for MySQL support
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY php/ /var/www/html/
WORKDIR /var/www/html/

EXPOSE 80
