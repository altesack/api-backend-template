FROM php:8.3-fpm-alpine

# Install PHP extensions
RUN apk add icu-dev bash
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.6


# Set working directory
WORKDIR /var/www
