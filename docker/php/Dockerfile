FROM php:8.4-apache

# Install extensions
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ git unzip libzip-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install mysqli pdo pdo_mysql intl zip

# Enable mod_rewrite
RUN a2enmod rewrite

# Set document root ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Install Composer dependencies (jika ada composer.json)
RUN if [ -f "composer.json" ]; then composer install --no-dev --optimize-autoloader; fi