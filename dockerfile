# Use the official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files to the container's web root
COPY public/ /var/www/html/

# Set working directory to the web root
WORKDIR /var/www/html

# Expose port 80 for Apache
EXPOSE 80

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
