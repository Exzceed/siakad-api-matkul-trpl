FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev libicu-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql pgsql intl

# Install Composer agar folder vendor otomatis dibuat di server
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . /var/www/html
WORKDIR /var/www/html

# Install dependency CI4 (vendor) & atur izin folder writable
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html/writable
