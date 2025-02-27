# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

ENV DB_CONNECTION=pgsql
ENV DB_HOST=dpg-cutqua5ds78s7392lra0-a.oregon-postgres.render.com
ENV DB_PORT=5432
ENV DB_DATABASE=kalendar_prod
ENV DB_USERNAME=kalendar_prod_user
ENV DB_PASSWORD=yQ7U6fdAXEnb21s22XmWjDYLGXpKG4v0

# Establecemos el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Probar conexión a PostgreSQL
RUN apt-get update && apt-get install -y postgresql-client && \
    pg_isready -h ${DB_HOST} -U ${DB_USERNAME} -d ${DB_DATABASE} || echo "PostgreSQL connection failed"


# Instalamos dependencias del sistema necesarias para PostgreSQL, Composer y Node.js
RUN apt-get update && apt-get install -y \
    git unzip curl \
    libpq-dev \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Instalamos las extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_pgsql

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

# Iniciar el contenedor y ejecutar migraciones antes de arrancar Apache
CMD php artisan migrate --force && apache2-foreground

# Iniciamos Apache
CMD ["apache2-foreground"]

# Verificación importante de Composer y PostgreSQL
RUN echo "📦 Verificando dependencias de PHP y Composer:"
# Verificar Composer
RUN composer --version

# Verificar las extensiones de PHP necesarias para PostgreSQL
RUN php -m | grep -E 'pdo|pgsql'


# Verificar el estado de las migraciones de Laravel
RUN php artisan migrate:status

# Verificar los archivos generados por Vite en public/build
RUN echo "📂 Verificando archivos en public/build:" && ls -l /var/www/html/public/build
