FROM php:7.4-fpm
ARG TIMEZONE


RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev librabbitmq-dev libssh-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql bcmath mysqli sockets \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install amqp \
    && docker-php-ext-enable amqp


RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini

# Install Redis extension
RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

WORKDIR /var/www/html/project

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
