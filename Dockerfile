FROM debian:bookworm-slim

RUN apt-get update \
	&& apt-get install -y \
		imagemagick \
		nginx \
		openssl \
		php \
		php-apcu \
		php-cli \
		php-curl \
		php-fpm \
		php-gd \
		php-gmp \
		php-intl \
		php-json \
		php-mbstring \
		php-mysql \
		php-opcache \
		php-xml \
		php-zip \
		poppler-utils \
		python3 \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

COPY _codebase/w /opt/bluespice/w
COPY opt/bluespice/w/LocalSettings.php /opt/bluespice/w/LocalSettings.php

ADD https://raw.githubusercontent.com/hallowelt/docker-bluespice-formula/main/_client/mathoid-remote /usr/local/bin/mathoid-remote
COPY usr/local/bin /usr/local/bin
RUN chmod 755 /usr/local/bin/*

COPY etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/mods-available/90-bluespice-overrides.ini
RUN ln -s /etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/cli/conf.d/90-bluespice-overrides.ini
COPY etc/nginx/sites-enabled/default /etc/nginx/sites-enabled/default
COPY etc/php/8.2/fpm/pool.d/www.conf /etc/php/8.2/fpm/pool.d/www.conf

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint"]
