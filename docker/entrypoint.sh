#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

log() { printf '[entrypoint] %s\n' "$*"; }

# ----------------------------------------------------------------
# 1. APP_KEY — generar uno efímero si la variable no está seteada
#    (en Dokploy: setea APP_KEY como env var para persistir sesiones)
# ----------------------------------------------------------------
if [ -z "${APP_KEY:-}" ]; then
    APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    export APP_KEY
    log "APP_KEY efímera generada. Setea APP_KEY como env var en Dokploy para persistirla."
fi

# ----------------------------------------------------------------
# 2. Esperar a la base de datos (best-effort, 30s)
# ----------------------------------------------------------------
if [ -n "${DB_HOST:-}" ] && [ "${DB_CONNECTION:-mysql}" != "sqlite" ]; then
    DB_PORT_EFF="${DB_PORT:-3306}"
    log "Esperando base de datos en ${DB_HOST}:${DB_PORT_EFF}..."
    for i in $(seq 1 30); do
        if php -r "exit(@fsockopen(getenv('DB_HOST'), (int)(getenv('DB_PORT') ?: 3306), \$e, \$es, 2) ? 0 : 1);"; then
            log "Base de datos accesible."
            break
        fi
        sleep 1
        if [ "$i" -eq 30 ]; then
            log "ADVERTENCIA: la DB no respondió en 30s, continuando de todos modos."
        fi
    done
fi

# ----------------------------------------------------------------
# 3. Symlink de storage (necesario para servir imágenes del menú)
# ----------------------------------------------------------------
if [ ! -L public/storage ]; then
    php artisan storage:link --force || true
fi

# ----------------------------------------------------------------
# 4. Migraciones (idempotentes)
# ----------------------------------------------------------------
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    log "Ejecutando migraciones..."
    php artisan migrate --force --no-interaction
fi

# ----------------------------------------------------------------
# 5. Seed opcional (solo si RUN_SEED=true)
# ----------------------------------------------------------------
if [ "${RUN_SEED:-false}" = "true" ]; then
    log "Sembrando datos iniciales..."
    php artisan db:seed --force --no-interaction || log "Seed falló (puede que ya esté hecho)."
fi

# ----------------------------------------------------------------
# 6. Cachés de producción (config, route, view, package discovery)
# ----------------------------------------------------------------
php artisan package:discover --ansi || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ----------------------------------------------------------------
# 7. Permisos sobre storage / bootstrap (los volúmenes pueden resetearlos)
# ----------------------------------------------------------------
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

log "Boot completo. Lanzando supervisord."
exec "$@"
