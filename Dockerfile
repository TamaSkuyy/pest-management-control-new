FROM dunglas/frankenphp:latest-php8.3

COPY --chown=82:82 . /app
WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN install-php-extensions pdo_mysql zip bcmath pcntl opcache

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
