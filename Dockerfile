FROM php:8.2-apache

# Installer les dépendances PHP et système
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libpq-dev libpng-dev libonig-dev libicu-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip bcmath intl mbstring gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite
RUN a2enmod rewrite

# Définir DocumentRoot sur public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copier les fichiers composer pour profiter du cache
COPY composer.json composer.lock /var/www/html/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Copier le reste du code
COPY . /var/www/html

# Permissions correctes
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Optimiser Laravel
RUN php /var/www/html/artisan config:cache && \
    php /var/www/html/artisan route:cache && \
    php /var/www/html/artisan view:cache