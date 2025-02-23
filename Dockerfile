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


# Verifica si el archivo de base de datos existe antes de migrar
RUN if [ ! -f /opt/render/storage/database.sqlite ]; then echo "Database does not exist"; fi

# Ejecuta las migraciones de Laravel (con --force si es necesario)
RUN php artisan migrate --force

# Verifica el estado de las migraciones
RUN php artisan migrate:status

# Verifica las tablas en la base de datos
RUN echo "Verificando tablas SQLite..." && sqlite3 /opt/render/storage/database.sqlite ".tables"
RUN echo "Contenido de la base de datos:" && sqlite3 /opt/render/storage/database.sqlite "SELECT name FROM sqlite_master WHERE type='table';"

# Verifica la estructura del directorio
RUN ls -l /opt/render/storage
RUN ls -l /var/www/html/database/migrations




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

RUN echo "Verificación de dependencias........................................"
# Verificar Composer
RUN composer --version

# Verificar sqlite3
RUN sqlite3 --version

# Verificar las extensiones de PHP (PDO y PDO_SQLite)
RUN php -m | grep -E 'pdo|sqlite'

# Verificar si las dependencias de Composer están instaladas correctamente
RUN composer show

# Verificar si las dependencias de frontend con pnpm están instaladas
RUN pnpm list

# Verificar el estado de las migraciones de Laravel
RUN php artisan migrate:status

# Verificar la versión de Laravel
RUN php artisan --version

# Verificar las tablas en la base de datos SQLite
RUN echo "Verificando tablas SQLite..." && sqlite3 /opt/render/storage/database.sqlite ".tables"
RUN echo "Contenido de la base de datos:" && sqlite3 /opt/render/storage/database.sqlite "SELECT name FROM sqlite_master WHERE type='table';"

# Verificar los archivos en el directorio de almacenamiento
RUN ls -la /opt/render/storage
RUN ls -la /var/www/html/database/migrations

# Verificar si la base de datos fue creada correctamente
RUN if [ -f /opt/render/storage/database.sqlite ]; then echo "Database exists"; else echo "Database does not exist"; fi

# Mostrar el contenido del archivo .env (en la ruta que corresponde a tu proyecto Laravel)
RUN cat /var/www/html/.env
