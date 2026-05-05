FROM node:16 AS node-builder
WORKDIR /app

# Ensure public asset dirs exist so final stage COPY won't fail
RUN mkdir -p public/js public/css

# Copy JS/CSS build files
COPY package*.json webpack.mix.js ./
COPY resources resources
RUN npm ci --silent
RUN npm run production --silent || true

FROM php:8.1-apache

# System deps
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Enable Apache rewrite module
RUN a2enmod rewrite

# Disable other MPMs to avoid conflicts (keep only mpm_prefork)
RUN a2dismod mpm_event mpm_worker || true

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app
COPY . /var/www/html

# Copy built assets from node stage (if any)
COPY --from=node-builder /app/public/js /var/www/html/public/js
COPY --from=node-builder /app/public/css /var/www/html/public/css

# Set document root to public (Laravel requirement)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-plugins

# Create storage/cache directories and set permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
