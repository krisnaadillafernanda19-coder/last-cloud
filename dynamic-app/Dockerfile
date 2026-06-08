FROM php:8.2-apache

# Install driver PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Ubah DocumentRoot Apache ke folder public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copy source code
COPY . /var/www/html/

# Set permission
RUN chown -R www-data:www-data /var/www/html
