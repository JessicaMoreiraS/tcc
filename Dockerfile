# Use the official PHP image as the base image
FROM php:8.0-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Expose port 80 (default for HTTP)
EXPOSE 80

# Copy your PHP application files into the working directory
COPY . /var/www/html

# Install any PHP extensions or libraries your application needs
# For example, to install the PDO extension for database connectivity:
RUN docker-php-ext-install pdo pdo_mysql

# Define the entry point for the container
CMD ["apache2-foreground"]
