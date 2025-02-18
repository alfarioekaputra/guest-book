FROM dunglas/frankenphp:latest

# Install required extensions and dependencies
RUN install-php-extensions \
    intl \
    mysqli \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    exif

COPY ./Caddyfile /etc/caddy/Caddyfile

# Set working directory
WORKDIR /app

# Copy the CodeIgniter 4 application
COPY . /app

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/writable

# Configure PHP
RUN cp /app/env /app/.env \
    && sed -i 's/CI_ENVIRONMENT = development/CI_ENVIRONMENT = development/' /app/.env \
    && sed -i 's/# app.baseURL = ''/app.baseURL = https:\/\/guest-book.test/' /app/.env

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Update Composer and install dependencies
RUN composer update \
    && composer install --no-dev --optimize-autoloader

# Expose port for FrankenPHP
EXPOSE 80

# Use the Caddyfile from the compose setup
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]