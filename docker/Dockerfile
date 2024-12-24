FROM dunglas/frankenphp

# Copy your Laravel application
COPY . /app
WORKDIR /app

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app

# FrankenPHP configuration
COPY Caddyfile /etc/caddy/Caddyfile
