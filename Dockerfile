FROM debian:bullseye-slim as base
ENV TZ=CET
ENV LANG=C.UTF-8
ENV LC_ALL=C.UTF-8
ENV DEBIAN_FRONTEND=noninteractive
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
 && apt-get update  \
 && apt-get -y install --no-install-recommends gnupg2 curl  \
# && touch /etc/apt/sources.list.d/trixie.list && printf "deb http://deb.debian.org/debian trixie main" > /etc/apt/sources.list.d/trixie.list \
 && apt-get update  
# && apt-get --only-upgrade install zlib1g
FROM base as bluespice-main
RUN apt-get -y --no-install-recommends install \
	cron \
	openssl \
	ca-certificates \
	imagemagick \
	dvipng \
	nginx \
	php \
	php-fpm \
	php-xml \
	php-mbstring \
	php-curl \
	php-zip \
	php-cli \
	php-json \
	php-mysql \
	php-ldap \
	php-opcache \
	php-apcu \
	php-intl \
	php-gd \
	php-gmp \
	poppler-utils \
	python3 \
	librsvg2-2 \
	librsvg2-bin \
	librsvg2-common \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

FROM bluespice-main as bluespice-prepare
RUN mkdir -p /app/bluespice \
	&& cd /app/bluespice
COPY --chown=www-data:www-data ./_codebase/bluespice /app/bluespice/w
COPY ./_codebase/simplesamlphp/ /app/simplesamlphp
RUN chown www-data: /app/simplesamlphp/public
RUN ln -s /app/simplesamlphp/public /app/bluespice/_sp
COPY ./root-fs/etc/nginx/sites-enabled/* /etc/nginx/sites-enabled
COPY ./root-fs/etc/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./root-fs/app/bin /app/bin
COPY ./root-fs/app/conf /app/conf
ADD https://github.com/hallowelt/misc-mediawiki-adm/releases/latest/download/mediawiki-adm /app/bin
ADD https://github.com/hallowelt/misc-parallel-runjobs-service/releases/download/1.0.0/parallel-runjobs-service /app/bin
COPY ./root-fs/etc/php/8.x/fpm/conf.d/* /etc/php/7.4/fpm/conf.d
COPY ./root-fs/etc/php/8.x/fpm/php-fpm.conf /etc/php/7.4/fpm/
COPY ./root-fs/etc/php/8.x/fpm/pool.d/www.conf /etc/php/7.4/fpm/pool.d/
COPY ./root-fs/etc/php/8.x/cli/conf.d/* /etc/php/7.4/cli/conf.d/
COPY ./root-fs/etc/php/8.x/mods-available /etc/php/7.4/mods-available
FROM bluespice-prepare as bluespice-final
ENV PATH="/app/bin:${PATH}" 
RUN chmod 755 /app/bin/* \
 && ln -s /app/simplesamlphp/public /app/bluespice/_sp
RUN  apt-get -y auto-remove \
 && apt-get -y clean \
 && apt-get -y autoclean \
 && rm -Rf /usr/share/doc \
 && find /var/log -type f -delete \
 && rm -Rf /var/lib/apt/lists/* \
 && rm -fr /tmp/*
 ARG UID
 ARG GID
 ENV UID=1002
 ENV GID=1002
 RUN addgroup --gid $GID bluespice \
  && adduser --uid $UID --gid $GID --disabled-password --gecos "" bluespice \
  && usermod -aG www-data bluespice \
  && chown -R 1002:1002 /app/bin \
  && chown -R 1002:1002 /app/conf 
#  && chown bluespice:www-data /var/run/php 
WORKDIR /app
#USER bluespice
EXPOSE 9090
ENTRYPOINT ["/app/bin/entrypoint"]
