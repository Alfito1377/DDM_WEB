FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative


FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
      libpng \
      libzip \
      zlib \
      oniguruma \
      freetype \
      libjpeg-turbo \
      ca-certificates \
      curl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
      pdo_mysql \
      gd \
      zip \
      bcmath \
      pcntl \
      redis \
      opcache \
      sockets \
  && rm -rf /var/cache/apk/* /tmp/*

COPY docker/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 9000

USER www-data

CMD ["php-fpm"]
