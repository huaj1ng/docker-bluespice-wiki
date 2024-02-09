FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y \
	openssl \
	imagemagick \
	dvipng \
	php-xml \
	php-mbstring \
	php-curl \
	php-zip \
	php-cli \
	php-json \
	php-mysql \
	php-opcache \
	php-apcu \
	php-intl \
	php-gd \
	php-gmp \
	poppler-utils \
	python3 \
	librsvg2-bin \
	vim \
	mariadb-client \
	&& rm -rf /var/lib/apt/lists/*

COPY _codebase/w /opt/bluespice/w
ADD https://raw.githubusercontent.com/hallowelt/docker-bluespice-formula/main/_client/mathoid-remote /usr/local/bin/mathoid-remote
RUN chmod +x /usr/local/bin/mathoid-remote

# Here we only have PHP-CLI installed. For the apache configwe place a symlink to this file in the web container
COPY etc/php/8.2/cli/conf.d/90-bluespice-overrides.ini /etc/php/8.2/cli/conf.d/90-bluespice-overrides.ini
