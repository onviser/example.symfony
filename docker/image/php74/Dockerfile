FROM debian:9.12

RUN apt-get update -y && apt-get upgrade -y && apt-get install -y git-core curl apt-transport-https lsb-release ca-certificates
RUN curl -o /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list

#RUN apt-get update && apt-get install -y git
#RUN apt-get update && apt-get install -y unzip
#RUN apt-get update && apt-get install -y imagemagick

RUN useradd -ms /bin/bash develop && usermod -a -G www-data develop

RUN apt-get update && apt-get install -y \
    php7.4 \
    php7.4-curl \
    php7.4-fpm \
    php7.4-gd \
    php7.4-json \
    php7.4-mbstring \
    php7.4-mysql \
    php7.4-pgsql \
    php7.4-readline \
    php7.4-xml \
    php7.4-zip \
    php-imagick \
    php-memcache \
    php-xdebug

RUN sed -i '/^listen /c \
    listen = 9000' /etc/php/7.4/fpm/pool.d/www.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /etc/php/7.4/fpm/conf.d/40-custom.ini

RUN service php7.4-fpm start

VOLUME ["/var/www"]
WORKDIR /var/www

EXPOSE 9000

ENTRYPOINT ["php-fpm7.4", "--nodaemonize"]