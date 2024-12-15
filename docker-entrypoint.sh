#!/bin/bash
set -e

echo "Starting entrypoint script..."

# Dump environment variables (masking sensitive info)
echo "Environment variables:"
echo "MONGODB_URI: ${MONGODB_URI//:*@/:****@}"
echo "MONGODB_DATABASE: $MONGODB_DATABASE"
echo "PORT: $PORT"
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "APP_URL: $APP_URL"

# Vérifier les variables d'environnement requises
if [ -z "$MONGODB_URI" ]; then
    echo "Error: MONGODB_URI is not set"
    exit 1
fi

if [ -z "$MONGODB_DATABASE" ]; then
    echo "Error: MONGODB_DATABASE is not set"
    exit 1
fi

# Configure Apache to use the PORT environment variable
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf

# Configure PHP
echo "Configuring PHP..."
{
    echo "error_reporting = E_ALL"
    echo "display_errors = On"
    echo "log_errors = On"
    echo "error_log = /dev/stderr"
    echo "extension=mongodb.so"
    echo "mongodb.debug=1"
} >> /usr/local/etc/php/php.ini

# Configure Apache
echo "Configuring Apache..."
{
    echo "LogLevel debug"
    echo "ErrorLog /dev/stderr"
    echo "ServerName localhost"
} >> /etc/apache2/apache2.conf

# Test database connection
echo "Testing database connection..."
if ! php /var/www/html/scripts/test_db.php; then
    echo "Warning: Database connection test failed. Check the logs above for details."
    # Ne pas quitter, permettre à Apache de démarrer pour afficher la page d'erreur
fi

# Initialize database if test was successful
if [ $? -eq 0 ]; then
    echo "Initializing database..."
    if ! php /var/www/html/scripts/init_db.php; then
        echo "Warning: Database initialization failed. Check the logs above for details."
    fi
fi

echo "Starting Apache..."
exec apache2-foreground
