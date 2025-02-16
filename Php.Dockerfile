FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install \
    mysqli \
    pdo_mysql \
    mbstring \
    zip \
    && docker-php-ext-enable mysqli pdo_mysql
COPY ./app /var/www/html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
