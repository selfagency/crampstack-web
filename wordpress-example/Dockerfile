ARG PORT

# Install WordPress and Composer packages
FROM composer:latest as vendor
WORKDIR /tmp/composer
COPY composer.json composer.json
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --prefer-dist

FROM selfagency/crampstack-web
# Copy WordPress
COPY --from=vendor --chown=www-data:www-data /tmp/composer/wordpress /var/www/html/
# Copy Composer support files
COPY --from=vendor --chown=www-data:www-data /tmp/composer/vendor /var/www/html/vendor/
# Link wp-cli to /usr/local/bin
RUN ln -s /var/www/html/vendor/wp-cli/wp-cli/bin/wp /usr/local/bin/wp

# Copy themes and plugins from repo
COPY --chown=www-data:www-data ./wp-content/themes/ /var/www/html/wp-content/themes/
COPY --chown=www-data:www-data ./wp-content/plugins/ /var/www/html/wp-content/plugins/
# Copy custom files
COPY --chown=www-data:www-data wp-config.php /var/www/html/wp-config.php
COPY Caddyfile /etc/caddy/Caddyfile
COPY custom.ini /usr/local/etc/php/conf.d/custom.ini

# Expose port
EXPOSE ${PORT}

# Run it!
CMD touch /var/www/html/wp-content/debug.log; \
  php-fpm -eF& \
  tail -f /var/www/html/wp-content/debug.log& \
  caddy run --config=/etc/caddy/Caddyfile --adapter=caddyfile
