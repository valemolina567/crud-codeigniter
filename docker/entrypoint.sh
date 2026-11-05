#!/bin/sh
set -e
cd /var/www/html

# Render indica el puerto en $PORT (por defecto 10000)
PORT="${PORT:-10000}"
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Genera el .env a partir de las variables de entorno del servicio (el .env real no se sube a Git)
cat > .env <<ENV
CI_ENVIRONMENT = ${CI_ENVIRONMENT:-production}
app.baseURL = '${APP_BASE_URL}'
database.default.hostname = ${DB_HOST}
database.default.database = ${DB_NAME}
database.default.username = ${DB_USER}
database.default.password = '${DB_PASSWORD}'
database.default.DBDriver = MySQLi
database.default.port = ${DB_PORT:-3306}
ENV

# Crea/actualiza las tablas en cada arranque (es seguro repetirlo)
php spark migrate || echo "AVISO: la migración falló, revisa las variables DB_*"

# Crea el usuario administrador si se define ADMIN_PASSWORD (no se guarda en Git)
if [ -n "${ADMIN_PASSWORD}" ]; then
    php spark db:seed AdminSeeder || true
fi

# Datos de ejemplo solo si se pide con SEED=1
if [ "${SEED}" = "1" ]; then
    php spark db:seed CoreSeeder || true
fi

chown -R www-data:www-data writable public/uploads
exec apache2-foreground
