# Base image
FROM dunglas/frankenphp:latest

# Set working directory
WORKDIR /srv/app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /srv/app

# Expose the port used by FrankenPHP
EXPOSE 80

CMD php artisan migrate --force

# Command to start FrankenPHP
CMD ["frankenphp", "serve", "--port=80", "--config=/srv/app/frankenphp.conf"]
