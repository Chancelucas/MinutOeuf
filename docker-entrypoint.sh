#!/bin/bash
set -e

echo "Starting entrypoint script..."

# Dump ALL environment variables (masking sensitive info)
echo "=== Environment Variables ==="
env | sort | while read -r line; do
    if [[ $line == MONGODB_URI* ]]; then
        echo "MONGODB_URI: [MASKED]"
    elif [[ $line == *PASSWORD* ]] || [[ $line == *SECRET* ]]; then
        echo "[MASKED SECRET]"
    else
        echo "$line"
    fi
done
echo "=========================="

# Export variables to Apache environment
echo "Exporting variables to Apache..."
echo "SetEnv MONGODB_URI ${MONGODB_URI}" >> /etc/apache2/conf-enabled/environment.conf
echo "SetEnv MONGODB_DATABASE ${MONGODB_DATABASE}" >> /etc/apache2/conf-enabled/environment.conf

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

# Create a PHP info file for debugging
echo "<?php phpinfo(); ?>" > /var/www/html/public/info.php

# Test database connection with detailed output
echo "Testing database connection..."
php /var/www/html/scripts/test_db.php
DB_TEST_RESULT=$?

if [ $DB_TEST_RESULT -ne 0 ]; then
    echo "Warning: Database connection test failed! Check the logs above for details."
    # Continue anyway to show error page
fi

# Initialize database if test was successful
if [ $DB_TEST_RESULT -eq 0 ]; then
    echo "Initializing database..."
    if ! php /var/www/html/scripts/init_db.php; then
        echo "Warning: Database initialization failed. Check the logs above for details."
    fi
fi

echo "Starting Apache..."
exec apache2-foreground
