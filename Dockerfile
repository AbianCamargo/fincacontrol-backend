FROM php:8.4-fpm-alpine

# Instala dependencias del sistema necesarias para Laravel y PostgreSQL
RUN apk add --no-cache \
    postgresql-dev \
    zip \
    unzip \
    git \
    curl \
    nginx

# Instala las extensiones de PHP que Laravel necesita
RUN docker-php-ext-install pdo pdo_pgsql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copia el código del proyecto
COPY . .

# Instala las dependencias de PHP en modo producción
RUN composer install --no-dev --optimize-autoloader

# Da permisos de escritura a las carpetas que Laravel necesita
RUN chmod -R 775 storage bootstrap/cache

# Puerto que Render usará para conectarse
EXPOSE 8080

# Comando que arranca el servidor al iniciar el contenedor
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8080