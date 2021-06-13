# CRAMPStack Web Layer - selfagency/crampstack-web

FROM wordpress:php7.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
  autoconf \
  build-base \
  ca-certificates \
  caddy \
  curl \
  mailcap \
  make \
  micro \
  nss-tools \
  openssl-dev \
  unzip \
  wget \
  zlib;

RUN pecl channel-update pecl.php.net; \
  pecl config-set php_ini /usr/local/etc/php/php.ini; \
  pecl install redis;

RUN docker-php-ext-enable redis;

# Configure Caddy
RUN [ ! -e /etc/nsswitch.conf ] && echo 'hosts: files dns' > /etc/nsswitch.conf;

# Configure PHP
RUN sed -i "s/;clear_env/clear_env/" /usr/local/etc/php-fpm.d/www.conf;

# Run it!
CMD php-fpm& \
  caddy run --config=/etc/caddy/Caddyfile --adapter=caddyfile;