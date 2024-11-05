# Base stage for PHP dependencies
FROM bitnami/laravel:10.3.3 AS php-base

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Node.js stage for frontend build
FROM node:16 AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# Development stage
FROM php-base AS dev

COPY --from=node-builder --chown=bitnami:bitnami /app/node_modules ./node_modules
COPY --chown=bitnami:bitnami . .

# Set up storage and bootstrap cache directories
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chown -R bitnami:bitnami storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Run post-install scripts
RUN composer run-script post-autoload-dump \
    && composer run-script post-update-cmd

USER bitnami

EXPOSE 8000

ENTRYPOINT php artisan migrate && php artisan serve --host=0.0.0.0 --port=8000

# Production stage
FROM php-base AS prod

COPY --chown=bitnami:bitnami . .
COPY --from=node-builder --chown=bitnami:bitnami /app/public/build /app/public/build

# Install dependencies first without --no-dev
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Copy and set up PHP-FPM configuration
COPY --chown=bitnami:bitnami php-fpm.conf /opt/bitnami/etc/php-fpm.conf
RUN chmod 644 /opt/bitnami/etc/php-fpm.conf

# Run Laravel optimization commands AFTER setting permissions
USER root

# Now install without dev dependencies for production
RUN rm -rf vendor \
    && composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --optimize-autoloader

USER bitnami

EXPOSE 9000

ENTRYPOINT ["sh", "-c", "php artisan migrate --force && php artisan config:cache && exec php-fpm -F --fpm-config /opt/bitnami/etc/php-fpm.conf"]
