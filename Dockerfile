# Use Bref PHP 8.3 FPM image (Lambda runtime compatible)
FROM bref/php-83-fpm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/task

# Copy composer files first (cache dependencies)
COPY composer.json composer.lock ./

# Install dependencies (production only)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress

# Copy rest of Laravel project
COPY . .

# Fix permissions
RUN chmod -R 777 storage bootstrap/cache

# Environment variables (Docker defaults, will be overridden by Lambda)
ENV APP_ENV=production
ENV APP_DEBUG=false

# Lambda handler (for HTTP via API Gateway)
CMD ["public/index.php"]
