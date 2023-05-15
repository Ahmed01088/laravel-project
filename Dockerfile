FROM php:8.2.0-apache
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN composer install --no-interaction --optimize-autoloader
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
