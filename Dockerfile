# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy your PHP files to the web server's document root
COPY index.php /var/www/html/

# Copy your .htaccess file
COPY .htaccess /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
