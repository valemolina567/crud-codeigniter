# ---------- Imagen para desplegar CodeIgniter 4 en Render (PHP 8.2 + Apache) ----------
FROM php:8.2-apache

# Extensiones que necesita CodeIgniter 4 + MySQL
RUN apt-get update && apt-get install -y --no-install-recommends libicu-dev libzip-dev unzip git \
    && docker-php-ext-install intl mysqli zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Apache sirve la carpeta /public del proyecto
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p writable/cache writable/logs writable/session writable/uploads \
        public/uploads/personajes public/uploads/animes \
    && chown -R www-data:www-data writable public/uploads

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 10000
CMD ["/usr/local/bin/entrypoint.sh"]
