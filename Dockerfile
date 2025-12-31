# syntax=docker/dockerfile:1

FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    mysql-client \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd \
       --with-freetype=/usr/include/ \
       --with-jpeg=/usr/include/ \
       --with-webp=/usr/include/ \
    && docker-php-ext-install \
       pdo_mysql \
       mbstring \
       bcmath \
       gd \
       zip \
    && rm -rf /var/cache/apk/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy package files first for better caching
COPY package.json package-lock.json* ./

# Install Node dependencies
RUN npm ci --only=production || npm install

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copy app (but exclude vendor from context)
COPY . /var/www/html

# Build frontend assets
RUN npm run build

# Remove Node.js and npm (not needed in production)
RUN apk del nodejs npm

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy PHP-FPM pool config
RUN mkdir -p /usr/local/etc/php-fpm.d
COPY docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy Supervisor config
RUN mkdir -p /etc/supervisor/conf.d
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy PHP configuration for file uploads
COPY docker/php.ini /usr/local/etc/php/conf.d/uploads.ini

# Create socket and log directories
RUN mkdir -p /run /var/log/nginx && \
    chown www-data:www-data /run && \
    chown -R www-data:www-data /var/log/nginx

# Fix nginx directory permissions (required to persist across rebuilds)
RUN mkdir -p /var/lib/nginx/tmp && \
    chmod -R 755 /var/lib/nginx

# Entry Script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/entrypoint.sh"]
