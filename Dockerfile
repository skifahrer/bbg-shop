# Base stage
FROM dunglas/frankenphp:1-php8.3 AS app

WORKDIR /app

# Install additional PHP extensions and Composer
RUN install-php-extensions \
    ctype \
    iconv \
    intl \
    pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Development stage
FROM app AS app_dev

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash && \
    apt-get install -y symfony-cli

# Create directory structure and set permissions
RUN mkdir -p var/cache var/log vendor && \
    chown -R www-data:www-data /app

# Copy composer files first
COPY --chown=www-data:www-data composer.* symfony.* ./

# Install dependencies as www-data
USER www-data
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Copy rest of the application
COPY --chown=www-data:www-data . .

# Run Symfony specific commands
RUN composer run-script post-install-cmd

USER root
RUN chmod +x bin/console && \
    chmod -R 777 var

USER www-data
RUN bin/console doctrine:migrations:migrate --no-interaction

EXPOSE 8000

CMD ["symfony", "serve", "--no-tls", "--allow-http", "--port=8000"]

# Production stage
FROM app AS app_prod

# Create directory structure and set permissions
RUN mkdir -p var/cache var/log vendor && \
    chown -R www-data:www-data /app

# Copy composer files first
COPY --chown=www-data:www-data composer.* symfony.* ./

# Switch to www-data user for Composer operations
USER www-data

RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Copy rest of the application
COPY --chown=www-data:www-data . .

RUN composer install --no-dev --optimize-autoloader && \
    composer dump-env prod && \
    composer run-script post-install-cmd && \
    composer dump-autoload --classmap-authoritative --no-dev

USER root
RUN chmod +x bin/console

USER www-data
RUN bin/console cache:clear --no-warmup && \
    bin/console cache:warmup && \
    bin/console doctrine:migrations:migrate --no-interaction

EXPOSE 80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
