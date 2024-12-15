#!/bin/bash
set -e

echo "Starting entrypoint script..."
echo "Environment variables:"
echo "MONGODB_URI: ${MONGODB_URI//:*@/:****@}"
echo "MONGODB_DATABASE: $MONGODB_DATABASE"
echo "PORT: $PORT"

# Configure Apache to use the PORT environment variable
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf

# Wait for MongoDB to be ready
echo "Waiting for MongoDB connection..."
for i in {1..30}; do
    if php -r "
        require '/var/www/html/vendor/autoload.php';
        \$client = new MongoDB\Client('$MONGODB_URI');
        try {
            \$client->selectDatabase('$MONGODB_DATABASE')->command(['ping' => 1]);
            echo 'Connected to MongoDB successfully\n';
            exit(0);
        } catch (Exception \$e) {
            echo \$e->getMessage() . '\n';
            exit(1);
        }
    "; then
        break
    fi
    echo "Attempt $i: Waiting for MongoDB connection..."
    sleep 2
done

echo "Initializing database..."
php /var/www/html/scripts/init_db.php

echo "Starting Apache..."
apache2-foreground
