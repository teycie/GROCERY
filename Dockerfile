FROM node:16 AS node-builder
WORKDIR /app

# Ensure public asset dirs exist so final stage COPY won't fail
RUN mkdir -p public/js public/css

# Copy JS/CSS build files
COPY package*.json webpack.mix.js ./
COPY resources resources
RUN npm ci --silent
RUN npm run production --silent || true

FROM php:8.1-cli

# System deps
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app
COPY . /var/www/html

# Copy built assets from node stage (if any)
COPY --from=node-builder /app/public/js /var/www/html/public/js
COPY --from=node-builder /app/public/css /var/www/html/public/css

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-plugins

# Create storage/cache directories and set permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R nobody:nogroup /var/www/html/storage /var/www/html/bootstrap/cache

# Create a simple startup script to run Laravel with PHP built-in server
RUN echo '#!/bin/sh\ncd /var/www/html\nexec php -S 0.0.0.0:80 -t public' > /start.sh && \
    chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
