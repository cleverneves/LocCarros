FROM php:8.1-fpm-alpine

ARG user=loccarros
ARG uid=1000

# Install runtime libs, compile all extensions and remove build tools in a single layer
RUN apk add --no-cache git zip unzip libpq oniguruma \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    oniguruma-dev \
    postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql mbstring pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && rm -rf /tmp/pear

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN adduser -D -u $uid -h /home/$user $user \
    && addgroup $user www-data \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

WORKDIR /var/www

USER $user
