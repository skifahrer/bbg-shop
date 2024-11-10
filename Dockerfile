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

COPY composer.* symfony.* ./

RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

COPY . .

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer run-script post-install-cmd; \
    chmod +x bin/console; \
    bin/console doctrine:migrations:migrate --no-interaction; \
    chmod -R 777 var/cache var/log

EXPOSE 8000

CMD ["symfony", "serve", "--no-tls", "--allow-http", "--port=8000"]

# Production stage
FROM app AS app_prod

COPY composer.* symfony.* ./

RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

COPY . .

RUN set -eux; \
    composer install --no-dev --optimize-autoloader; \
    composer dump-env prod; \
    composer run-script post-install-cmd; \
    chmod +x bin/console; \
    bin/console cache:clear --no-warmup; \
    bin/console cache:warmup; \
    bin/console doctrine:migrations:migrate --no-interaction; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    chown -R 1000:1000 var

USER 1000:1000

EXPOSE 80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
