# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html

# Enable Apache mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Expose port 80 (Apache)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
