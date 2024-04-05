FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y \
	apache2 \
	libapache2-mod-php \
	openssl \
	imagemagick \
	dvipng \
	php \
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
	php-fpm \
	poppler-utils \
	python3 \
	librsvg2-bin \
	&& rm -rf /var/lib/apt/lists/*

COPY _codebase/w /opt/bluespice/w
RUN sed -i '/return; \/\/ Disabled. Needs Tomcat/d' /opt/bluespice/w/settings.d/020-BlueSpiceExtendedSearch.php \
	&& sed -i '/return; \/\/ Disabled. Needs Tomcat/d' _codebase/w/settings.d/020-BlueSpiceUEModulePDF.php
COPY opt/bluespice/w/LocalSettings.php /opt/bluespice/w/LocalSettings.php

ADD https://raw.githubusercontent.com/hallowelt/docker-bluespice-formula/main/_client/mathoid-remote /usr/local/bin/mathoid-remote
COPY usr/local/bin /usr/local/bin
RUN chmod +x /usr/local/bin/*

COPY etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/mods-available/90-bluespice-overrides.ini
RUN ln -s /etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/cli/conf.d/90-bluespice-overrides.ini \
	&& ln -s /etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/apache2/conf.d/90-bluespice-overrides.ini
COPY etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf

ENTRYPOINT ["/usr/local/bin/entrypoint"]
