# Use the official PHP image with Apache
FROM php:8.2-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev  \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

RUN docker-php-ext-install zip
# Enable mod_rewrite for Laravel
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html/ims/backend

# Copy the Laravel project into the container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/ims
RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap/cache

# Expose port 80 for the web server
EXPOSE 80
