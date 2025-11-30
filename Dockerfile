FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \ 
    git \
    unzip \
    libzip-dev \
    zip \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

# Copiar composer.json antes para aproveitar cache
COPY composer.json composer.lock ./

RUN composer install --no-progress --no-scripts --prefer-dist --no-interaction || true

# Copiar todo o código (o volume em docker-compose sobrescreve)
COPY . .

# Permissões (storage, bootstrap cache)
RUN chown -R www-data:www-data /var/www/backend/storage /var/www/backend/bootstrap/cache || true
RUN chmod -R 775 /var/www/backend/storage /var/www/backend/bootstrap/cache || true

EXPOSE 9000

CMD ["php-fpm"]
