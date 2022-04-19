# update from laradock/laradock 

#################################
# PHP 相关
#################################
FROM laradock/workspace:latest-7.4

LABEL maintainer="qingwuit <bishashiwo@gmail.com>"

# If you're in China, or you need to change sources , Change application source from deb.debian.org to aliyun source
ARG CHANGE_SOURCE=false
RUN sed -i 's/deb.debian.org/mirrors.tuna.tsinghua.edu.cn/' /etc/apt/sources.list && \
    sed -i 's/security.debian.org/mirrors.tuna.tsinghua.edu.cn/' /etc/apt/sources.list && \
    sed -i 's/security-cdn.debian.org/mirrors.tuna.tsinghua.edu.cn/' /etc/apt/sources.list \
;fi

# always run apt update when start and after add new source list, then clean up at end.
RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      apt-utils \
      gnupg2 \
      git \
      libzip-dev zip unzip && \
      docker-php-ext-configure zip; \
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'

USER root

RUN pecl install -o -f redis; \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    docker-php-ext-install pcntl \
    docker-php-ext-install mysqli \
;fi

COPY ../ /var/www:cached
COPY ./php-fpm/laravel.ini /usr/local/etc/php/conf.d
COPY ./php-fpm/xlaravel.pool.conf /usr/local/etc/php-fpm.d/
COPY ./php-fpm/php7.4.ini /usr/local/etc/php/php.ini

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN groupmod -o -g 1000 www-data && \
    usermod -o -u 1000 -g www-data www-data

WORKDIR /var/www

CMD ["php-fpm"]

# EXPOSE 9000

#################################
# Nginx
#################################
FROM nginx:alpine
COPY ./nginx/nginx.conf /etc/nginx/

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/' /etc/apk/repositories 

RUN apk update \
    && apk upgrade \
    && apk --update add logrotate \
    && apk add --no-cache openssl \
    && apk add --no-cache bash

RUN apk add --no-cache curl

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

# Create 'messages' file used from 'logrotate'
RUN touch /var/log/messages

# Copy 'logrotate' config file
COPY ./nginx/logrotate/nginx /etc/logrotate.d/
COPY ./nginx/sites/ /etc/nginx/sites-available

# Set upstream conf and remove the default conf
RUN echo "upstream php-upstream { server php-fpm:9000; }" > /etc/nginx/conf.d/upstream.conf \
    && rm /etc/nginx/conf.d/default.conf

ADD ./nginx/startup.sh /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh
CMD ["/bin/bash", "/opt/startup.sh"]

# EXPOSE 80 81 443


#################################
# Mysql
#################################

FROM mysql:5.7.36

#####################################
# Set Timezone
#####################################

RUN ln -snf /usr/share/zoneinfo/PRC /etc/localtime && echo PRC > /etc/timezone && chown -R mysql:root /var/lib/mysql/

COPY ./mysql/my.cnf /etc/mysql/conf.d/my.cnf
COPY ./mysql/docker-entrypoint-initdb.d /docker-entrypoint-initdb.d

RUN rm -rf /etc/mysql/data \
    && chmod 0444 /etc/mysql/conf.d/my.cnf \
    && mkdir -p /etc/mysql/custum/data/

CMD ["mysqld"]

# EXPOSE 3306


# ImageMagick:
# USER root

# ARG INSTALL_IMAGEMAGICK=false
# ARG IMAGEMAGICK_VERSION=latest
# ENV IMAGEMAGICK_VERSION ${IMAGEMAGICK_VERSION}

# RUN if [ ${INSTALL_IMAGEMAGICK} = true ]; then \
#     apt-get install -yqq libmagickwand-dev imagemagick && \
#     if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
#       cd /tmp && \
#       if [ ${IMAGEMAGICK_VERSION} = "latest" ]; then \
#         git clone https://github.com/Imagick/imagick; \
#       else \
#         git clone --branch ${IMAGEMAGICK_VERSION} https://github.com/Imagick/imagick; \
#       fi && \
#       cd imagick && \
#       phpize && \
#       ./configure && \
#       make && \
#       make install && \
#       rm -r /tmp/imagick; \
#     else \
#       pecl install imagick; \
#     fi && \
#     docker-php-ext-enable imagick; \
#     php -m | grep -q 'imagick' \
# ;fi