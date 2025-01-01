# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip

# Install PHP extensions for MySQL (mysqli, pdo, pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set the working directory for Apache
WORKDIR /var/www/html

# Copy the project files into the container
# This will copy the entire context into the container
COPY . /var/www/html/

# Copy custom Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/

# Set file permissions so Apache can serve the files correctly
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground (default for this image)
CMD ["apache2-foreground"]
