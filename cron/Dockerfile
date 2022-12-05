
FROM  php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

COPY crontab /etc/crontabs/root
RUN mkdir /var/log/cron

CMD ["crond", "-f"]