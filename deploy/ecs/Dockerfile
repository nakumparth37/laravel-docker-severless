FROM php:8.3-fpm-alpine as php-base

# Install dependencies
RUN apk add --no-cache \
    nginx \
    git \
    zip \
    unzip \
    curl \
    bash \
    supervisor \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    oniguruma-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql bcmath exif pcntl gd opcache \
    && docker-php-ext-enable gd bcmath exif opcache

# Install Composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer


# Workdir
WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy .env.production as .env
COPY .env .env

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx & Supervisor configs
COPY ./deploy/ecs/nginx.conf /etc/nginx/nginx.conf
COPY ./deploy/ecs/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
