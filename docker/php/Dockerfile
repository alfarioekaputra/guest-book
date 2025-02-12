# Stage 1: Build stage (optional if you need to compile assets)
FROM composer:2 AS composer

# Stage 2: Final runtime image
FROM php:8.4-cli-alpine AS runtime

# Set environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1 \
  APP_ENV=development

# Install necessary system dependencies
RUN apk add --no-cache \
  bash \
  git \
  curl \
  libzip-dev \
  libpng-dev \
  libjpeg-turbo-dev \
  freetype-dev \
  icu-dev \
  oniguruma-dev \
  libxml2-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
  gd \
  pdo_mysql \
  mysqli \
  zip \
  intl \
  opcache \
  mbstring \
  xml

# Copy Composer from the build stage
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html

# Expose port (if needed for a web server)
# EXPOSE 8081

# Start the application (e.g., using PHP built-in server for development)
# CMD ["php", "spark", "serve", "--host 0.0.0.0", "--port 8081"]