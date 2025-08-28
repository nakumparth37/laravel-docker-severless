#!/bin/sh
# build_lambda.sh

# Run migrations if MIGRATE environment variable is set
if [ "$MIGRATE" = "true" ]; then
    echo "Running Laravel migrations..."
    php artisan migrate --force
fi

# Start Laravel app
exec php-fpm
