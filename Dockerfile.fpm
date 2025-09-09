# Use Bref PHP 8.3 FPM image (Lambda runtime compatible)
FROM bref/php-83-fpm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/task

# Copy everything first (so artisan exists before composer install)
COPY . .

# Install dependencies (production only)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress

# Fix permissions
RUN chmod -R 777 storage bootstrap/cache

# Clear Laravel caches
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Environment variables
ENV APP_ENV=local
ENV APP_DEBUG=true

# Lambda handler (for HTTP via API Gateway)
CMD ["public/index.php"]
