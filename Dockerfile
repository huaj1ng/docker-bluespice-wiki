FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y \
	nginx \
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
	php-openssl \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

COPY _codebase/w /opt/bluespice/w
#RUN sed -i '/return; \/\/ Disabled. Needs Tomcat/d' /opt/bluespice/w/settings.d/020-BlueSpiceExtendedSearch.php \
#	&& sed -i '/return; \/\/ Disabled. Needs Tomcat/d' /opt/bluespice/w/settings.d/020-BlueSpiceUEModulePDF.php
COPY opt/bluespice/w/LocalSettings.php /opt/bluespice/w/LocalSettings.php

ADD https://raw.githubusercontent.com/hallowelt/docker-bluespice-formula/main/_client/mathoid-remote /usr/local/bin/mathoid-remote
COPY usr/local/bin /usr/local/bin
RUN chmod +x /usr/local/bin/*

COPY etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/mods-available/90-bluespice-overrides.ini
RUN ln -s /etc/php/8.2/mods-available/90-bluespice-overrides.ini /etc/php/8.2/cli/conf.d/90-bluespice-overrides.ini
COPY etc/nginx/sites-enabled/default /etc/nginx/sites-enabled/default

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint"]
