# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Instalamos dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip curl sqlite3 libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalamos las extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_sqlite

# Instalamos Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalamos Node.js y pnpm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs \
    && npm install -g pnpm

# Copiamos los archivos del proyecto
COPY . .

# Instalamos las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Instalamos las dependencias de frontend con pnpm
RUN pnpm install && pnpm run build

# Configuramos permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponemos el puerto 80
EXPOSE 80

# Creamos la base de datos si no existe
RUN mkdir -p /opt/render/storage && touch /opt/render/storage/database.sqlite

# Iniciamos Apache
CMD ["apache2-foreground"]
