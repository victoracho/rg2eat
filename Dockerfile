FROM php:8.3-apache

# 1. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev libfreetype6-dev \
    libjpeg62-turbo-dev libicu-dev libsqlite3-dev \
    supervisor ffmpeg \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_sqlite mbstring exif pcntl bcmath zip intl

# 3. Optimización de Memoria PHP y límites de subida de archivos
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN { \
      echo "upload_max_filesize=100M"; \
      echo "post_max_size=110M"; \
      echo "max_file_uploads=20"; \
      echo "max_execution_time=300"; \
      echo "max_input_time=300"; \
    } > /usr/local/etc/php/conf.d/uploads.ini

# 4. Apache y Composer
RUN a2enmod rewrite headers
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Node.js 16 (Estable para Vue 2 / Mix 5)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && apt-get install -y nodejs

WORKDIR /var/www/html

# 6. Dependencias PHP
COPY composer.json composer.lock artisan ./
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 7. Copiar el resto del código
COPY . /var/www/html

# 8. Permisos iniciales
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Compilar Assets con parche de permisos
RUN mkdir -p /var/www/html/public && \
    chmod -R 777 /var/www/html/public && \
    mkdir -p /.npm-cache && \
    chmod -R 777 /.npm-cache && \
    npm config set cache /.npm-cache --global && \
    rm -rf node_modules package-lock.json && \
    npm install --legacy-peer-deps && \
    if [ -f "webpack.mix.js" ]; then npm run prod; else npm run build; fi

# 10. Configuración de Apache para Laravel (Forzar el sitio)
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/laravel.conf && \
    a2dissite 000-default.conf && \
    a2ensite laravel.conf

# 11. Finalizar carga de clases
RUN composer dump-autoload --optimize --no-dev && \
    sed -i 's/\r$//' /var/www/html/entrypoint.sh && \
    chmod +x /var/www/html/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/entrypoint.sh"]
