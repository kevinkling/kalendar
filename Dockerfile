# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Establecemos el directorio de trabajo dentro del contenedor
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

# Copiamos los archivos del proyecto al directorio de trabajo
COPY . .

# Instalamos las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Instalamos las dependencias de frontend con pnpm
RUN pnpm install && pnpm run build

# Creamos la base de datos SQLite si no existe
RUN mkdir -p /opt/render/storage && touch /opt/render/storage/database.sqlite

RUN chown -R www-data:www-data /opt/render/storage
RUN chmod -R 777 /opt/render/storage


# Ejecutamos las migraciones de Laravel (incluyendo la de sesiones)
RUN php artisan migrate --force

# Verifica si las migraciones pendientes
RUN php artisan migrate:status

RUN ls -la /var/www/html/database/migrations
RUN ls -la /opt/render/storage


# Verificar si la base de datos fue creada
RUN if [ -f /opt/render/storage/database.sqlite ]; then echo "Database exists"; else echo "Database does not exist"; fi
# Verificar tablas en la base de datos
RUN sqlite3 /opt/render/storage/database.sqlite "SELECT name FROM sqlite_master WHERE type='table';"


# Verificar tablas en SQLite
RUN ls -l /opt/render/storage/database.sqlite
RUN sqlite3 /opt/render/storage/database.sqlite ".tables"
RUN echo "Verificando tablas SQLite..." && sqlite3 /opt/render/storage/database.sqlite ".tables"



# Habilitamos mod_rewrite para URLs amigables en Laravel
RUN a2enmod rewrite

# Cambiamos el DocumentRoot a la carpeta public de Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Configuramos DirectoryIndex para reconocer index.php
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Creamos el archivo .env y configuramos permisos
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configuramos los permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache public
RUN chmod -R 775 storage bootstrap/cache public

# Exponemos el puerto 80
EXPOSE 80

# Iniciamos Apache
CMD ["apache2-foreground"]
