# update from laradock/laradock 

#################################
# PHP 相关
#################################
FROM laradock/php-fpm:latest-7.4

LABEL maintainer="qingwuit <bishashiwo@gmail.com>"

# If you're in China, or you need to change sources , Change application source from deb.debian.org to aliyun source
RUN apt-get clean \
    && cd /var/lib/apt \
    && mv lists lists.old \
    && mkdir -p lists/partial \
    && apt-get clean \
    && echo "deb http://mirrors.aliyun.com/debian/ bullseye main non-free contrib \n \
            deb-src http://mirrors.aliyun.com/debian/ bullseye main non-free contrib \n \
            deb http://mirrors.aliyun.com/debian-security/ bullseye-security main \n \
            deb-src http://mirrors.aliyun.com/debian-security/ bullseye-security main \n \
            deb http://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib \n \
            deb-src http://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib \n \
            deb http://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib \n \
            deb-src http://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" > /etc/apt/sources.list \
        # 更新及安装库
        && apt-get update \
        && apt-get install -y libmagickwand-dev libmcrypt-dev libpq-dev libzip-dev zip \
        # PHP扩展安装
        && docker-php-ext-install -j$(nproc) pdo_mysql bcmath \
        && pecl install redis imagick mcrypt zip \
        && docker-php-ext-enable redis imagick mcrypt zip 


USER root

RUN pecl install -o -f redis; \
    rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install mysqli 

COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./xlaravel.pool.conf /usr/local/etc/php-fpm.d/
COPY ./php7.4.ini /usr/local/etc/php/php.ini

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN groupmod -o -g 1000 www-data && \
    usermod -o -u 1000 -g www-data www-data

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000