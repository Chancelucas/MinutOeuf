FROM php:8.2-apache

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    zip \
    unzip \
    git \
    ca-certificates \
    openssl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction

# Copy the rest of the application
COPY . .

# Final composer optimization
RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data /var/www/html

# Configure PHP error reporting and MongoDB
RUN echo "error_reporting = E_ALL" > /usr/local/etc/php/conf.d/error-reporting.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/error-reporting.ini \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini \
    && echo "mongodb.debug=off" >> /usr/local/etc/php/conf.d/mongodb.ini

# Download MongoDB CA certificate
RUN curl -fsSL https://www.mongodb.org/static/pgp/server-7.0.asc | gpg --dearmor -o /usr/share/keyrings/mongodb-archive-keyring.gpg \
    && curl -fsSL https://raw.githubusercontent.com/mongodb/mongo/master/src/mongo/util/net/server.pem -o /etc/ssl/certs/mongodb.pem \
    && chmod 644 /etc/ssl/certs/mongodb.pem

# Make entrypoint script executable
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Use custom entrypoint script
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

# Start Apache in foreground
CMD ["apache2-foreground"]
