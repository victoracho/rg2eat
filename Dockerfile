FROM php:8.4-apache

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    TZ=Europe/Lisbon

# 1. Dependencias del sistema (Debian Trixie)
RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl ca-certificates tzdata \
        libpng-dev libonig-dev libxml2-dev \
        zip unzip libzip-dev \
        libfreetype-dev libjpeg-dev libwebp-dev \
        libicu-dev libsqlite3-dev \
 && cp /usr/share/zoneinfo/${TZ} /etc/localtime \
 && echo "${TZ}" > /etc/timezone \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" \
        gd pdo_mysql pdo_sqlite mbstring exif pcntl bcmath zip intl opcache

# 3. Ajustes PHP (memoria, uploads, opcache)
RUN { \
      echo "memory_limit=256M"; \
      echo "upload_max_filesize=20M"; \
      echo "post_max_size=20M"; \
      echo "max_file_uploads=20"; \
      echo "max_execution_time=120"; \
      echo "max_input_time=120"; \
      echo "expose_php=Off"; \
      echo "date.timezone=${TZ}"; \
    } > /usr/local/etc/php/conf.d/zz-app.ini \
 && { \
      echo "opcache.enable=1"; \
      echo "opcache.enable_cli=0"; \
      echo "opcache.memory_consumption=128"; \
      echo "opcache.interned_strings_buffer=16"; \
      echo "opcache.max_accelerated_files=10000"; \
      echo "opcache.validate_timestamps=0"; \
      echo "opcache.revalidate_freq=0"; \
      echo "opcache.fast_shutdown=1"; \
    } > /usr/local/etc/php/conf.d/zz-opcache.ini

# 4. Apache + Composer
RUN a2enmod rewrite headers \
 && a2dissite 000-default.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Dependencias PHP (sin scripts ni autoload aún: el código todavía no está)
COPY composer.json composer.lock ./
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
        --no-dev --no-scripts --no-autoloader \
        --prefer-dist --no-interaction --no-progress

# 6. Código de la aplicación
COPY . /var/www/html

# 7. Autoload optimizado + estructura de storage + permisos
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
 && mkdir -p \
        storage/app/public \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwX storage bootstrap/cache

# 8. VirtualHost de Apache apuntando a public/
RUN printf '%s\n' \
      '<VirtualHost *:80>' \
      '    DocumentRoot /var/www/html/public' \
      '    <Directory /var/www/html/public>' \
      '        Options -Indexes +FollowSymLinks' \
      '        AllowOverride All' \
      '        Require all granted' \
      '    </Directory>' \
      '    ErrorLog  ${APACHE_LOG_DIR}/error.log' \
      '    CustomLog ${APACHE_LOG_DIR}/access.log combined' \
      '</VirtualHost>' > /etc/apache2/sites-available/laravel.conf \
 && a2ensite laravel.conf

# 9. Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
 && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
