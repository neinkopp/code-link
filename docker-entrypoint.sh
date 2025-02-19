#!/bin/sh
set -e

# Determine the role of the container
ROLE=${CONTAINER_ROLE:-app}

# If the first argument starts with a hyphen, assume php-fpm is intended
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

# Run these commands regardless of the role
php /var/www/artisan migrate --force
php /var/www/artisan optimize:clear
php /var/www/artisan optimize
php /var/www/artisan package:discover --ansi

# Execute based on the container role
if [ "$ROLE" = "app" ]; then
    # Start php-fpm
    exec "$@"
elif [ "$ROLE" = "queue" ]; then
    # Run the queue worker
    echo "Starting queue worker..."
    exec php artisan queue:work --verbose --tries=3 --timeout=90
else
    echo "Unknown container role: $ROLE"
    exit 1
fi