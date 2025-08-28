# Use Bref's PHP 8.3 FPM image (Lambda runtime compatible)
FROM bref/php-83-fpm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory to Lambda's /var/task
WORKDIR /var/task

# Copy application code
COPY . .

# Install Laravel dependencies (production only)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress

# Fix permissions for Laravel storage and cache
RUN chmod -R 777 storage bootstrap/cache

# Set environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# The Lambda handler is provided by Bref, so leave CMD empty
CMD ["public/index.php"]
