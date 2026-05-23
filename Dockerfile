# Stage 1: Build frontend assets with Node
FROM node:18-alpine AS frontend
WORKDIR /build
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

## Composer stage: run composer install with PHP 8.2 CLI to avoid platform mismatches
FROM php:8.4-cli-alpine AS composer
WORKDIR /app
RUN apk add --no-cache \
    git \
    unzip \
    zip \
    libzip-dev \
    oniguruma-dev \
    zlib-dev \
    autoconf \
    g++ \
    make
RUN docker-php-ext-install -j$(nproc) zip mbstring bcmath
RUN pecl install mongodb && docker-php-ext-enable mongodb
COPY composer.json composer.lock ./
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction

## Final runtime stage: PHP-FPM with required extensions
FROM php:8.4-fpm-alpine
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libzip-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install -j$(nproc) \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    pdo

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copy application code into final image
COPY . .

# Copy built frontend assets from node stage
COPY --from=frontend /build/public/build public/build

# Copy vendor from composer stage
COPY --from=composer /app/vendor ./vendor

# Create storage and bootstrap cache directories and set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Set environment to production
ENV APP_ENV=production
ENV APP_DEBUG=false

# Expose port (Render will bind to $PORT)
EXPOSE 8000

# Start PHP built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
