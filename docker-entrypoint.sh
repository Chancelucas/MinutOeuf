#!/bin/bash
set -e

echo "Starting entrypoint script..."
echo "Environment variables:"
echo "MONGODB_URI: ${MONGODB_URI//:*@/:****@}"
echo "MONGODB_DATABASE: $MONGODB_DATABASE"
echo "PORT: $PORT"
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "APP_URL: $APP_URL"

# Configure Apache to use the PORT environment variable
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf

# Test database connection
echo "Testing database connection..."
php /var/www/html/scripts/test_db.php
if [ $? -ne 0 ]; then
    echo "Database connection test failed!"
    exit 1
fi

echo "Initializing database..."
php /var/www/html/scripts/init_db.php
if [ $? -ne 0 ]; then
    echo "Database initialization failed!"
    exit 1
fi

# Enable Apache error logging
echo "Configuring Apache error logging..."
echo "LogLevel debug" >> /etc/apache2/apache2.conf
echo "ErrorLog /dev/stderr" >> /etc/apache2/apache2.conf

# Enable PHP error logging
echo "Configuring PHP error logging..."
echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini
echo "display_errors = On" >> /usr/local/etc/php/php.ini
echo "log_errors = On" >> /usr/local/etc/php/php.ini
echo "error_log = /dev/stderr" >> /usr/local/etc/php/php.ini

echo "Starting Apache..."
apache2-foreground
