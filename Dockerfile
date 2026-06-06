# syntax=docker/dockerfile:1.7

# ============================================================
# Stage 1 — vendor: PHP dependencies (production-only)
# ============================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress \
 && rm -rf /root/.composer /tmp/*

# ============================================================
# Stage 2 — app: runtime image (nginx + php-fpm + supervisor)
# ============================================================
FROM php:8.4-fpm-alpine AS app

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PHP_MEMORY_LIMIT=256M \
    TZ=Europe/Lisbon

# OS deps + PHP extensions
RUN set -eux; \
    apk add --no-cache \
        bash curl tzdata nginx supervisor \
        icu-libs oniguruma libpng libjpeg-turbo libwebp freetype libzip; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev oniguruma-dev libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev libzip-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
    docker-php-ext-install -j"$(nproc)" \
        bcmath gd intl mbstring opcache pcntl pdo_mysql zip; \
    apk del --no-network .build-deps; \
    cp /usr/share/zoneinfo/${TZ} /etc/localtime; \
    echo "${TZ}" > /etc/timezone; \
    mkdir -p /run/nginx /var/lib/nginx/tmp /var/log/nginx; \
    chown -R www-data:www-data /run/nginx /var/lib/nginx /var/log/nginx; \
    rm -rf /var/cache/apk/* /tmp/*

# Composer binary (handy for shelling into the container)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Application source
COPY --chown=www-data:www-data . .

# Vendor from stage 1 + finalize autoload (no scripts: APP env not ready yet)
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative --no-scripts \
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

# Runtime config
COPY docker/php.ini           /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php-fpm.pool.conf /usr/local/etc/php-fpm.d/zz-pool.conf
COPY docker/nginx.conf        /etc/nginx/nginx.conf
COPY docker/supervisord.conf  /etc/supervisord.conf
COPY docker/entrypoint.sh     /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1:8080/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf", "-n"]
